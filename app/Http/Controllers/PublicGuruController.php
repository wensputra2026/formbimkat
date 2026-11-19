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
        $tpgChoices = Guru::TPG_CHOICES;
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
     * Cek Status Pendataan Mandiri oleh Guru
     */
    public function cekStatus(Request $request)
    {
        $guruResult = null;
        $error = null;

        if ($request->isMethod('post')) {
            $request->validate([
                'nomor_registrasi' => ['required', 'string'],
                'digit_verifikasi' => ['required', 'string', 'size:4'],
            ], [
                'nomor_registrasi.required' => 'Nomor registrasi wajib diisi.',
                'digit_verifikasi.required' => '4 digit terakhir NIP/NIK wajib diisi.',
                'digit_verifikasi.size' => 'Digit verifikasi harus tepat 4 angka.',
            ]);

            $nomorReg = trim($request->input('nomor_registrasi'));
            $digit = trim($request->input('digit_verifikasi'));

            $guru = Guru::with(['sekolah'])
                ->where('nomor_registrasi', $nomorReg)
                ->first();

            if (!$guru) {
                $error = 'Nomor registrasi tidak ditemukan dalam sistem.';
            } else {
                // Verifikasi 4 digit terakhir NIP atau NIK
                $last4Nip = $guru->nip ? substr($guru->nip, -4) : '';
                $last4Nik = $guru->nik ? substr($guru->nik, -4) : '';

                if ($digit !== $last4Nip && $digit !== $last4Nik) {
                    $error = 'Kombinasi Nomor Registrasi dan 4 digit terakhir NIP/NIK tidak cocok.';
                } else {
                    // Berhasil verifikasi - HANYA siapkan informasi publik yang aman
                    $guruResult = [
                        'nomor_registrasi' => $guru->nomor_registrasi,
                        'nama_sekolah' => $guru->sekolah ? $guru->sekolah->nama_sekolah : '-',
                        'status_verifikasi' => $guru->status_verifikasi,
                        'tanggal_pengajuan' => $guru->created_at->copy()->timezone('Asia/Makassar')->format('d/m/Y H:i:s') . ' WITA',
                        'alasan_penolakan' => $guru->status_verifikasi === 'Ditolak' ? $guru->alasan_penolakan : null,
                    ];
                }
            }
        }

        $appName = Setting::get('app_name', 'Pendataan Guru Kementerian Agama');

        return view('guru.cek_status', compact('guruResult', 'error', 'appName'));
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
