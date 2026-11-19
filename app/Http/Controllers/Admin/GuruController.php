<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuruUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\GuruHistory;
use App\Models\PangkatGolongan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    /**
     * Tampilkan data guru dengan filter dan submenu:
     * - semua
     * - pending (menunggu verifikasi)
     * - approved (disetujui)
     * - rejected (ditolak)
     * - trash (arsip data soft-deleted)
     */
    public function index(Request $request)
    {
        $statusTab = $request->query('tab', 'semua');

        $query = Guru::with(['sekolah', 'pangkatGolongan', 'verifier']);

        if ($statusTab === 'trash') {
            $query->onlyTrashed();
        } else {
            if ($statusTab === 'pending') {
                $query->where('status_verifikasi', 'Menunggu Verifikasi');
            } elseif ($statusTab === 'approved') {
                $query->where('status_verifikasi', 'Disetujui');
            } elseif ($statusTab === 'rejected') {
                $query->where('status_verifikasi', 'Ditolak');
            }
        }

        // Search
        if ($search = trim((string)$request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nomor_registrasi', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($sekolahId = $request->query('sekolah_id')) {
            $query->where('sekolah_id', $sekolahId);
        }
        if ($pangkatId = $request->query('pangkat_id')) {
            $query->where('pangkat_golongan_id', $pangkatId);
        }
        if ($tpg = $request->query('tpg')) {
            $query->where('penerima_tpg', $tpg);
        }
        if ($statusFilter = $request->query('status')) {
            $query->where('status_verifikasi', $statusFilter);
        }
        if ($kabupaten = $request->query('kabupaten')) {
            $query->whereHas('sekolah', function ($q) use ($kabupaten) {
                $q->where('kabupaten', $kabupaten);
            });
        }

        $gurus = $query->latest()->paginate(10)->withQueryString();

        // Hitung count untuk tabs
        $counts = [
            'semua' => Guru::count(),
            'pending' => Guru::where('status_verifikasi', 'Menunggu Verifikasi')->count(),
            'approved' => Guru::where('status_verifikasi', 'Disetujui')->count(),
            'rejected' => Guru::where('status_verifikasi', 'Ditolak')->count(),
            'trash' => Guru::onlyTrashed()->count(),
        ];

        $pangkats = PangkatGolongan::where('is_active', true)->orderBy('urutan')->get();
        $kabupatens = Sekolah::distinct()->whereNotNull('kabupaten')->pluck('kabupaten');

        return view('admin.guru.index', compact('gurus', 'counts', 'statusTab', 'pangkats', 'kabupatens'));
    }

    /**
     * Tampilkan detail data guru
     */
    public function show($id)
    {
        $guru = Guru::withTrashed()->with(['sekolah', 'pangkatGolongan', 'verifier'])->findOrFail($id);

        // Ambil riwayat audit log terkait guru ini
        $auditLogs = ActivityLog::with('user')
            ->where('subject_type', Guru::class)
            ->where('subject_id', $guru->id)
            ->latest('created_at')
            ->get();

        return view('admin.guru.show', compact('guru', 'auditLogs'));
    }

    /**
     * Formulir perbaikan / koreksi data oleh Admin
     */
    public function edit(Guru $guru)
    {
        $pangkats = PangkatGolongan::where('is_active', true)->orderBy('urutan')->get();
        $tpgChoices = Guru::TPG_CHOICES;
        $bulanChoices = Guru::BULAN;

        return view('admin.guru.edit', compact('guru', 'pangkats', 'tpgChoices', 'bulanChoices'));
    }

    /**
     * Simpan perbaikan data oleh Admin
     * Mencatat perubahan before dan after pada activity log
     */
    public function update(GuruUpdateRequest $request, Guru $guru)
    {
        $before = $guru->only([
            'nama', 'nip', 'nik', 'pangkat_golongan_id',
            'gaji_pokok', 'kgb_bulan', 'kgb_tahun', 'pensiun_bulan',
            'pensiun_tahun', 'penerima_tpg', 'sekolah_id'
        ]);

        $validated = $request->validated();
        $guru->fill($validated);
        $guru->save();

        $after = $guru->only([
            'nama', 'nip', 'nik', 'pangkat_golongan_id',
            'gaji_pokok', 'kgb_bulan', 'kgb_tahun', 'pensiun_bulan',
            'pensiun_tahun', 'penerima_tpg', 'sekolah_id'
        ]);

        // Catat audit trail perubahan
        GuruHistory::create([
            'guru_id' => $guru->id,
            'user_id' => Auth::id(),
            'action' => 'KOREKSI_DATA',
            'catatan' => 'Perbaikan data dilakukan oleh Administrator ' . Auth::user()->name,
            'old_values' => $before,
            'new_values' => $after,
            'ip_address' => $request->ip(),
        ]);

        ActivityLog::log(
            'update_guru',
            "Admin " . Auth::user()->name . " melakukan perbaikan data Guru: {$guru->nama} ({$guru->nomor_registrasi})",
            $guru,
            [
                'sebelum' => $before,
                'sesudah' => $after,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
            ]
        );

        return redirect()->route('admin.guru.show', $guru->id)
            ->with('success', 'Data Guru berhasil diperbaiki dan dicatat dalam log aktivitas.');
    }

    /**
     * Verifikasi data guru (Setujui atau Tolak dengan alasan wajib)
     */
    public function verify(Request $request, Guru $guru)
    {
        $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'alasan_penolakan' => ['required_if:action,reject', 'nullable', 'string', 'max:1000'],
        ], [
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi jika menolak permohonan data.',
        ]);

        $action = $request->input('action');
        $admin = Auth::user();

        if ($action === 'approve') {
            $guru->status_verifikasi = 'Disetujui';
            $guru->alasan_penolakan = null;
            $guru->diverifikasi_oleh = $admin->id;
            $guru->diverifikasi_pada = now();
            $guru->save();

            ActivityLog::log(
                'approve_guru',
                "Admin {$admin->name} MENYETUJUI data Guru: {$guru->nama} ({$guru->nomor_registrasi})",
                $guru
            );

            return back()->with('success', "Data Guru {$guru->nama} berhasil DISETUJUI.");
        } else {
            $guru->status_verifikasi = 'Ditolak';
            $guru->alasan_penolakan = $request->input('alasan_penolakan');
            $guru->diverifikasi_oleh = $admin->id;
            $guru->diverifikasi_pada = now();
            $guru->save();

            ActivityLog::log(
                'reject_guru',
                "Admin {$admin->name} MENOLAK data Guru: {$guru->nama} ({$guru->nomor_registrasi}) dengan alasan: {$guru->alasan_penolakan}",
                $guru,
                ['alasan' => $guru->alasan_penolakan]
            );

            return back()->with('warning', "Data Guru {$guru->nama} telah DITOLAK.");
        }
    }

    /**
     * Soft delete data guru
     */
    public function destroy(Guru $guru)
    {
        $nama = $guru->nama;
        $noReg = $guru->nomor_registrasi;

        $guru->delete();

        ActivityLog::log(
            'delete_guru',
            "Admin " . Auth::user()->name . " menghapus (soft-delete) data Guru: {$nama} ({$noReg})",
            $guru
        );

        return back()->with('success', "Data Guru {$nama} berhasil dipindahkan ke arsip data.");
    }

    /**
     * Pulihkan data guru dari arsip
     */
    public function restore($id)
    {
        $guru = Guru::onlyTrashed()->findOrFail($id);
        $guru->restore();

        ActivityLog::log(
            'restore_guru',
            "Admin " . Auth::user()->name . " memulihkan data Guru: {$guru->nama} ({$guru->nomor_registrasi}) dari arsip",
            $guru
        );

        return back()->with('success', "Data Guru {$guru->nama} berhasil dipulihkan dari arsip.");
    }
}
