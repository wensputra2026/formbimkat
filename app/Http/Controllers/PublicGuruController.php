<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuruRegistrationRequest;
use App\Models\Guru;
use App\Models\ActivityLog;
use App\Models\PangkatGolongan;
use App\Models\Sekolah;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicGuruController extends Controller
{
    /**
     * Halaman Utama / Formulir Pendataan Guru
     * http://127.0.0.1:8000/ langsung diarahkan ke form isian
     */
    public function create()
    {
        $pangkats = PangkatGolongan::where('is_active', true)->orderBy('urutan')->get();
        $tpgChoices = Guru::getTpgChoices();
        $bulanChoices = Guru::BULAN;
        $appName = Setting::get('app_name', 'Pendataan Guru Kementerian Agama');
        $instansi = Setting::get('instansi', 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan');
        $pengumuman = Setting::get('pengumuman');

        return view('guru.create', compact(
            'pangkats',
            'tpgChoices',
            'bulanChoices',
            'appName',
            'instansi',
            'pengumuman'
        ));
    }

    /**
     * Simpan Formulir Pendataan Publik
     */
    public function store(GuruRegistrationRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Generate nomor registrasi unik otomatis
            $nomorRegistrasi = Guru::generateNomorRegistrasi();

            $guru = new Guru();
            $guru->nomor_registrasi = $nomorRegistrasi;
            $guru->nama = $validated['nama'];
            $guru->nip = !empty($validated['nip']) ? $validated['nip'] : null;
            $guru->nik = !empty($validated['nik']) ? $validated['nik'] : null;
            $guru->pangkat_golongan_id = $validated['pangkat_golongan_id'];
            $guru->gaji_pokok = $validated['gaji_pokok'];
            $guru->kgb_bulan = $validated['kgb_bulan'];
            $guru->kgb_tahun = $validated['kgb_tahun'];
            $guru->pensiun_bulan = $validated['pensiun_bulan'];
            $guru->pensiun_tahun = $validated['pensiun_tahun'];
            $guru->penerima_tpg = $validated['penerima_tpg'] ?? null;
            $guru->sekolah_id = $validated['sekolah_id'];
            $guru->status_verifikasi = 'Disetujui';
            $guru->save();

            ActivityLog::log(
                'submit_form_guru',
                "Guru {$guru->nama} mengirim formulir pendataan dengan nomor registrasi {$guru->nomor_registrasi}.",
                $guru,
                [
                    'nomor_registrasi' => $guru->nomor_registrasi,
                    'sekolah' => $guru->sekolah?->nama_sekolah,
                ]
            );

            DB::commit();

            return redirect()->route('guru.sukses', ['nomor_registrasi' => $nomorRegistrasi])
                ->with('success', 'Formulir pendataan Anda telah berhasil dikirim!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan formulir guru: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data. Silakan coba kembali.');
        }
    }

    /**
     * Tampilkan Halaman Bukti Registrasi
     */
    public function sukses(string $nomor_registrasi)
    {
        $guru = Guru::with(['sekolah', 'pangkatGolongan'])
            ->where('nomor_registrasi', $nomor_registrasi)
            ->firstOrFail();

        $appName = Setting::get('app_name', 'Pendataan Guru Kementerian Agama');
        $instansi = Setting::get('instansi', 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan');

        return view('guru.sukses', compact('guru', 'appName', 'instansi'));
    }

    /**
     * Cek Status & Pencarian Data Mandiri oleh Guru (Bisa via NIP, NIK, atau No. Registrasi)
     */
    public function cekStatus(Request $request)
    {
        $guruResult = null;
        $error = null;

        if ($request->isMethod('post')) {
            $input = trim((string)($request->input('identitas') ?? $request->input('nomor_registrasi', '')));

            if (empty($input)) {
                return back()->withErrors(['identitas' => 'NIP, NIK, atau Nomor Registrasi wajib diisi.']);
            }

            $cleanNumeric = preg_replace('/[^0-9]/', '', $input);

            $guru = Guru::with(['sekolah', 'pangkatGolongan'])
                ->where('nomor_registrasi', $input)
                ->when(!empty($cleanNumeric), function ($q) use ($cleanNumeric) {
                    $q->orWhere('nip', $cleanNumeric)
                      ->orWhere('nik', $cleanNumeric);
                })
                ->orWhere('nip', $input)
                ->orWhere('nik', $input)
                ->first();

            if ($guru && $request->filled('digit_verifikasi')) {
                $last4Nip = $guru->nip ? substr($guru->nip, -4) : '';
                $last4Nik = $guru->nik ? substr($guru->nik, -4) : '';
                if ($request->input('digit_verifikasi') !== $last4Nip && $request->input('digit_verifikasi') !== $last4Nik) {
                    $guru = null;
                    $error = '4 digit terakhir NIP/NIK verifikasi tidak sesuai.';
                }
            }

            if (!$guru) {
                if (!$error) {
                    $error = "Data guru dengan identitas \"{$input}\" tidak ditemukan dalam sistem. Pastikan NIP atau NIK Anda sudah benar, atau silakan lakukan pengisian formulir pendataan terlebih dahulu.";
                }
            } else {
                // Berhasil verifikasi - simpan otorisasi di session
                session(['guru_edit_auth_' . $guru->nomor_registrasi => true]);

                $guruResult = [
                    'nomor_registrasi' => $guru->nomor_registrasi,
                    'nama' => $guru->nama,
                    'nip' => $guru->nip ? substr($guru->nip, 0, 4) . '********' . substr($guru->nip, -4) : '-',
                    'nik' => $guru->nik ? substr($guru->nik, 0, 4) . '********' . substr($guru->nik, -4) : '-',
                    'nama_sekolah' => $guru->sekolah ? $guru->sekolah->nama_sekolah : '-',
                    'pangkat' => $guru->pangkatGolongan ? $guru->pangkatGolongan->label : '-',
                    'status_verifikasi' => $guru->status_verifikasi,
                    'tanggal_pengajuan' => $guru->created_at->copy()->timezone('Asia/Makassar')->format('d/m/Y H:i:s') . ' WITA',
                    'alasan_penolakan' => $guru->status_verifikasi === 'Ditolak' ? $guru->alasan_penolakan : null,
                    'edit_url' => route('guru.edit_mandiri', ['nomor_registrasi' => $guru->nomor_registrasi]),
                ];
            }
        }

        $appName = Setting::get('app_name', 'Pendataan Guru Kementerian Agama');

        return view('guru.cek_status', compact('guruResult', 'error', 'appName'));
    }

    /**
     * Halaman Edit Formulir Mandiri oleh Guru
     */
    public function editMandiri(Request $request, string $nomor_registrasi)
    {
        $guru = Guru::with(['sekolah', 'pangkatGolongan'])
            ->where('nomor_registrasi', $nomor_registrasi)
            ->firstOrFail();

        // Cek otorisasi session dari Cek Status
        if (!session('guru_edit_auth_' . $nomor_registrasi)) {
            return redirect()->route('guru.cek_status')
                ->with('error', 'Silakan masukkan Nomor Registrasi dan 4 digit terakhir NIP/NIK terlebih dahulu untuk memverifikasi kepemilikan data sebelum mengubah formulir.');
        }

        $pangkats = PangkatGolongan::where('is_active', true)->orderBy('urutan')->get();
        $tpgChoices = Guru::getTpgChoices();
        $bulanChoices = Guru::BULAN;
        $appName = Setting::get('app_name', 'Pendataan Guru Kementerian Agama');
        $instansi = Setting::get('instansi', 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan');

        return view('guru.edit_mandiri', compact(
            'guru',
            'pangkats',
            'tpgChoices',
            'bulanChoices',
            'appName',
            'instansi'
        ));
    }

    /**
     * Simpan Perubahan Formulir Mandiri oleh Guru
     */
    public function updateMandiri(Request $request, string $nomor_registrasi)
    {
        $guru = Guru::where('nomor_registrasi', $nomor_registrasi)->firstOrFail();

        // Validasi input
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'nip' => ['nullable', 'digits:18', \Illuminate\Validation\Rule::unique('gurus', 'nip')->ignore($guru->id)],
            'nik' => ['nullable', 'digits:16', \Illuminate\Validation\Rule::unique('gurus', 'nik')->ignore($guru->id)],
            'pangkat_golongan_id' => ['required', 'exists:pangkat_golongans,id'],
            'gaji_pokok' => ['required', 'numeric', 'min:0'],
            'kgb_bulan' => ['required', 'integer', 'between:1,12'],
            'kgb_tahun' => ['required', 'integer', 'digits:4', 'min:1900', 'max:2100'],
            'pensiun_bulan' => ['required', 'integer', 'between:1,12'],
            'pensiun_tahun' => ['required', 'integer', 'digits:4', 'min:1900', 'max:2100'],
            'penerima_tpg' => ['nullable', \Illuminate\Validation\Rule::in(Guru::getTpgChoices())],
            'sekolah_id' => ['required', 'exists:sekolah,id_sekolah'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nip.digits' => 'NIP harus tepat 18 digit angka.',
            'nip.unique' => 'NIP sudah digunakan oleh guru lain.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik.unique' => 'NIK sudah digunakan oleh guru lain.',
            'pangkat_golongan_id.required' => 'Pangkat/Golongan wajib dipilih.',
            'gaji_pokok.required' => 'Gaji pokok saat ini wajib diisi.',
            'gaji_pokok.numeric' => 'Gaji pokok harus berupa angka.',
            'gaji_pokok.min' => 'Gaji pokok tidak boleh bernilai negatif.',
            'kgb_bulan.required' => 'Bulan berkala berikutnya wajib dipilih.',
            'kgb_tahun.required' => 'Tahun berkala berikutnya wajib diisi.',
            'kgb_tahun.digits' => 'Tahun berkala berikutnya harus 4 digit angka.',
            'pensiun_bulan.required' => 'Bulan pensiun wajib dipilih.',
            'pensiun_tahun.required' => 'Tahun pensiun wajib diisi.',
            'pensiun_tahun.digits' => 'Tahun pensiun harus 4 digit angka.',
            'sekolah_id.required' => 'Sekolah wajib dipilih dari daftar pencarian.',
            'sekolah_id.exists' => 'Sekolah yang dipilih tidak valid.',
            'penerima_tpg.in' => 'Pilihan Penerima TPG tidak valid.',
        ]);

        if (empty($validated['nip']) && empty($validated['nik'])) {
            return back()->withInput()->withErrors([
                'nip' => 'Minimal salah satu dari NIP atau NIK wajib diisi.',
                'nik' => 'Minimal salah satu dari NIP atau NIK wajib diisi.',
            ]);
        }

        DB::beginTransaction();
        try {
            $before = $guru->only([
                'nama', 'nip', 'nik', 'pangkat_golongan_id',
                'gaji_pokok', 'kgb_bulan', 'kgb_tahun', 'pensiun_bulan',
                'pensiun_tahun', 'penerima_tpg', 'sekolah_id'
            ]);

            $guru->nama = $validated['nama'];
            $guru->nip = !empty($validated['nip']) ? $validated['nip'] : null;
            $guru->nik = !empty($validated['nik']) ? $validated['nik'] : null;
            $guru->pangkat_golongan_id = $validated['pangkat_golongan_id'];
            $guru->gaji_pokok = $validated['gaji_pokok'];
            $guru->kgb_bulan = $validated['kgb_bulan'];
            $guru->kgb_tahun = $validated['kgb_tahun'];
            $guru->pensiun_bulan = $validated['pensiun_bulan'];
            $guru->pensiun_tahun = $validated['pensiun_tahun'];
            $guru->penerima_tpg = $validated['penerima_tpg'] ?? null;
            $guru->sekolah_id = $validated['sekolah_id'];
            $guru->save();

            $after = $guru->only([
                'nama', 'nip', 'nik', 'pangkat_golongan_id',
                'gaji_pokok', 'kgb_bulan', 'kgb_tahun', 'pensiun_bulan',
                'pensiun_tahun', 'penerima_tpg', 'sekolah_id'
            ]);

            ActivityLog::log(
                'update_mandiri_guru',
                "Guru {$guru->nama} memperbarui isian formulir pendataan secara mandiri ({$guru->nomor_registrasi}).",
                $guru,
                [
                    'sebelum' => $before,
                    'sesudah' => $after,
                    'ip' => $request->ip(),
                ]
            );

            DB::commit();

            return redirect()->route('guru.sukses', ['nomor_registrasi' => $guru->nomor_registrasi])
                ->with('success', 'Formulir pendataan Anda telah berhasil diperbaiki!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui formulir mandiri: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan perbaikan. Silakan coba kembali.');
        }
    }

    /**
     * AJAX Check Duplicate NIP atau NIK
     * Tidak membocorkan identitas pribadi guru yang sudah terdaftar
     */
    public function checkDuplicate(Request $request)
    {
        $type = $request->input('type');
        $value = trim((string)$request->input('value', ''));

        if (!in_array($type, ['nip', 'nik']) || empty($value)) {
            return response()->json(['valid' => false, 'message' => 'Tipe atau nilai tidak valid']);
        }

        $exists = Guru::where($type, $value)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? strtoupper($type) . ' ini sudah terdaftar dalam sistem.' : strtoupper($type) . ' dapat digunakan.',
        ]);
    }

    /**
     * Searchable Dropdown Sekolah untuk Alpine.js
     */
    public function cariSekolah(Request $request)
    {
        $term = trim((string)$request->input('q', ''));
        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $schools = Sekolah::where(function ($q) use ($term) {
            $q->where('nama_sekolah', 'like', "%{$term}%")
                ->orWhere('npsn', 'like', "%{$term}%");
        })
            ->orderBy('nama_sekolah')
            ->limit(20)
            ->get(['id_sekolah', 'nama_sekolah', 'npsn', 'jenjang', 'status_sekolah', 'kabupaten', 'provinsi']);

        return response()->json($schools);
    }
}
