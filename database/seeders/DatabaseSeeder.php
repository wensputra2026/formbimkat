<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@kemenag.go.id'],
            [
                'name' => 'Administrator Kemenag',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Juga buat jika ada env ADMIN_EMAIL
        if (env('ADMIN_EMAIL') && env('ADMIN_EMAIL') !== 'admin@kemenag.go.id') {
            User::updateOrCreate(
                ['email' => env('ADMIN_EMAIL')],
                [
                    'name' => 'Admin Kemenag',
                    'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')),
                    'role' => 'admin',
                ]
            );
        }

        // 2. Master Jabatan
        $jabatans = [
            'Guru Kelas',
            'Guru Mata Pelajaran',
            'Kepala Sekolah',
            'Wakil Kepala Sekolah',
            'Pengawas Sekolah',
            'Tenaga Administrasi',
            'Operator Sekolah',
            'Pustakawan',
            'Laboran',
        ];

        foreach ($jabatans as $jabatan) {
            DB::table('jabatans')->updateOrInsert(
                ['nama_jabatan' => $jabatan],
                ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // 3. Master Pangkat / Golongan
        $golongans = [
            ['golongan' => 'IX', 'pangkat' => 'Ahli Pertama (PPPK)', 'urutan' => 1],
            ['golongan' => 'X', 'pangkat' => 'Ahli Muda (PPPK)', 'urutan' => 2],
            ['golongan' => 'XI', 'pangkat' => 'Ahli Madya (PPPK)', 'urutan' => 3],
            ['golongan' => 'II/a', 'pangkat' => 'Pengatur Muda', 'urutan' => 4],
            ['golongan' => 'II/b', 'pangkat' => 'Pengatur Muda Tingkat I', 'urutan' => 5],
            ['golongan' => 'II/c', 'pangkat' => 'Pengatur', 'urutan' => 6],
            ['golongan' => 'II/d', 'pangkat' => 'Pengatur Tingkat I', 'urutan' => 7],
            ['golongan' => 'III/a', 'pangkat' => 'Penata Muda', 'urutan' => 8],
            ['golongan' => 'III/b', 'pangkat' => 'Penata Muda Tingkat I', 'urutan' => 9],
            ['golongan' => 'III/c', 'pangkat' => 'Penata', 'urutan' => 10],
            ['golongan' => 'III/d', 'pangkat' => 'Penata Tingkat I', 'urutan' => 11],
            ['golongan' => 'IV/a', 'pangkat' => 'Pembina', 'urutan' => 12],
            ['golongan' => 'IV/b', 'pangkat' => 'Pembina Tingkat I', 'urutan' => 13],
            ['golongan' => 'IV/c', 'pangkat' => 'Pembina Utama Muda', 'urutan' => 14],
            ['golongan' => 'IV/d', 'pangkat' => 'Pembina Utama Madya', 'urutan' => 15],
            ['golongan' => 'IV/e', 'pangkat' => 'Pembina Utama', 'urutan' => 16],
            ['golongan' => 'Lainnya', 'pangkat' => 'Non-PNS / Guru Honorer', 'urutan' => 17],
        ];

        foreach ($golongans as $gol) {
            DB::table('pangkat_golongans')->updateOrInsert(
                ['golongan' => $gol['golongan']],
                [
                    'pangkat' => $gol['pangkat'],
                    'urutan' => $gol['urutan'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 4. Default Settings
        $settings = [
            'app_name' => 'Pendataan Guru BIMAS Katolik',
            'instansi' => 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan',
            'tahun_pendataan' => '2026',
            'alamat_instansi' => 'Jalan Timor Raya, SoE, Kabupaten Timor Tengah Selatan, Nusa Tenggara Timur',
            'kontak' => '(0388) 21000',
            'email' => 'pendataanguru@kemenag.go.id',
            'pengumuman' => 'Selamat datang di Sistem Pendataan Guru Kementerian Agama. Silakan mengisi formulir data diri dengan lengkap, cermat, dan benar sesuai dokumen resmi Anda. Simpan Nomor Registrasi yang diterbitkan sebagai bukti pengajuan.',
        ];

        foreach ($settings as $key => $val) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $val, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
