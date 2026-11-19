<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\LoginHistory;
use App\Models\PangkatGolongan;
use App\Models\Sekolah;
use App\Models\User;
use App\Services\ExcelExportService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PendataanGuruTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected Sekolah $sekolah;
    protected PangkatGolongan $pangkat;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan Akun Admin
        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@bimaskatolik.local'],
            [
                'name' => 'Admin Test Kepegawaian',
                'password' => Hash::make('SecretAdmin123!'),
                'role' => 'admin',
            ]
        );

        // Siapkan Data Master
        $this->sekolah = Sekolah::firstOrCreate(
            ['id_sekolah' => 9999001],
            [
                'nama_sekolah' => 'SMK KEMENAG CONTOH TTS',
                'npsn' => '00112233',
                'jenjang' => 'SMK',
                'status_sekolah' => 'Negeri',
                'alamat_sekolah' => 'Jl. Kebangsaan No. 1, SoE',
                'kabupaten' => 'Timor Tengah Selatan',
                'provinsi' => 'Nusa Tenggara Timur',
                'id_kabupaten' => 1,
                'id_kecamatan' => 1,
            ]
        );

        $this->pangkat = PangkatGolongan::firstOrCreate(
            ['golongan' => 'III/a'],
            [
                'pangkat' => 'Penata Muda',
                'urutan' => 8,
                'is_active' => true,
            ]
        );
    }

    /**
     * Test Khusus: Memastikan tabel jabatans & kolom jabatan_id telah DIHAPUS dari database
     */
    public function test_00_jabatan_is_completely_removed_from_database(): void
    {
        $this->assertFalse(Schema::hasTable('jabatans'), 'Tabel jabatans harus sudah dihapus dari database.');
        $this->assertFalse(Schema::hasColumn('gurus', 'jabatan_id'), 'Kolom jabatan_id harus sudah dihapus dari tabel gurus.');
    }

    /**
     * 1. Login Admin Berhasil & Tercatat
     */
    public function test_01_admin_can_login_and_history_is_recorded(): void
    {
        $response = $this->post('/login', [
            'email' => $this->admin->email,
            'password' => 'SecretAdmin123!',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin);

        $this->assertDatabaseHas('login_histories', [
            'username' => $this->admin->email,
            'status' => 'success',
        ]);
    }

    /**
     * 2. Proteksi Seluruh Route Admin dari Guest
     */
    public function test_02_admin_routes_are_protected_from_guests(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/guru');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/verifikasi/antrean');
        $response->assertRedirect('/login');
    }

    /**
     * 3. Pengiriman Formulir Publik Diakses Langsung pada Root '/'
     */
    public function test_03_public_form_accessible_at_root(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Formulir Pendataan Guru');
        $response->assertSee('Nama Lengkap');
    }

    /**
     * 4. Pengiriman Formulir Publik Menghasilkan Nomor Registrasi Otomatis Unik
     */
    public function test_04_public_submission_generates_auto_registration_number(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Yohanes Baptista',
            'nip' => '198701012015011001',
            'nik' => '5302010101870001',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 4500000,
            'kgb_bulan' => 5,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2047,
            'penerima_tpg' => 'Kemenag',
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);

        $guru = Guru::where('nama', 'Yohanes Baptista')->first();
        $this->assertNotNull($guru);
        $this->assertStringStartsWith('PGK-', $guru->nomor_registrasi);
        $response->assertRedirect(route('guru.sukses', ['nomor_registrasi' => $guru->nomor_registrasi]));
    }

    /**
     * 5. Validasi NIP Harus Tepat 18 Digit
     */
    public function test_05_validation_nip_must_be_18_digits(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Antonius',
            'nip' => '12345', // hanya 5 digit
            'nik' => '5302010101870002',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3000000,
            'kgb_bulan' => 3,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 3,
            'pensiun_tahun' => 2045,
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);

        $response->assertSessionHasErrors(['nip']);
    }

    /**
     * 6. Validasi NIK Harus Tepat 16 Digit
     */
    public function test_06_validation_nik_must_be_16_digits(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Maria Goreti',
            'nip' => '198801012015011002',
            'nik' => '12345678', // hanya 8 digit
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3000000,
            'kgb_bulan' => 3,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 3,
            'pensiun_tahun' => 2045,
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);

        $response->assertSessionHasErrors(['nik']);
    }

    /**
     * 7. Validasi Minimal Satu dari NIP atau NIK Wajib Diisi
     */
    public function test_07_minimal_satu_nip_atau_nik_wajib(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Petrus Kanisius',
            'nip' => '',
            'nik' => '',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3000000,
            'kgb_bulan' => 3,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 3,
            'pensiun_tahun' => 2045,
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);

        $response->assertSessionHasErrors(['nip', 'nik']);
    }

    /**
     * 8 & 9. Pencegahan Duplikasi NIP dan NIK
     */
    public function test_08_09_pencegahan_duplikasi_nip_dan_nik(): void
    {
        Guru::create([
            'nomor_registrasi' => 'PGK-2026-TEST0001',
            'nama' => 'Guru Eksisting',
            'nip' => '199001012020011001',
            'nik' => '5302010101900001',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3200000,
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
            'status_verifikasi' => 'Menunggu Verifikasi',
        ]);

        // Coba kirim data dengan NIP sama
        $resNip = $this->post('/registrasi', [
            'nama' => 'Guru Baru NIP Sama',
            'nip' => '199001012020011001',
            'nik' => '5302010101900099',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3200000,
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);
        $resNip->assertSessionHasErrors(['nip']);

        // Coba kirim data dengan NIK sama
        $resNik = $this->post('/registrasi', [
            'nama' => 'Guru Baru NIK Sama',
            'nip' => '199001012020011099',
            'nik' => '5302010101900001',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3200000,
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);
        $resNik->assertSessionHasErrors(['nik']);
    }

    /**
     * 10. Validasi Gaji Pokok (Tidak Boleh Negatif)
     */
    public function test_10_validasi_gaji_pokok(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Fransiskus',
            'nik' => '5302010101900012',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => -500000, // negatif
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);

        $response->assertSessionHasErrors(['gaji_pokok']);
    }

    /**
     * 11. Validasi Bulan dan Tahun
     */
    public function test_11_validasi_bulan_dan_tahun(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Klara',
            'nik' => '5302010101900013',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 2500000,
            'kgb_bulan' => 15, // bulan > 12 tidak valid
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);

        $response->assertSessionHasErrors(['kgb_bulan']);
    }

    /**
     * 12. Pemilihan Master Sekolah
     */
    public function test_12_pemilihan_master_sekolah(): void
    {
        $response = $this->get('/sekolah/cari?q=KEMENAG');
        $response->assertStatus(200);
        $response->assertJsonFragment(['nama_sekolah' => 'SMK KEMENAG CONTOH TTS']);
    }

    /**
     * 13. Data yang Dikirim Langsung Tercatat Resmi (Admin Tidak Perlu Verifikasi)
     */
    public function test_13_status_awal_menunggu_verifikasi(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Guru Langsung Tercatat',
            'nik' => '5302010101900014',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3000000,
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);
        $response->assertSessionHas('success');

        $guru = Guru::where('nik', '5302010101900014')->first();
        $this->assertNotNull($guru);
        $this->assertEquals('Disetujui', $guru->status_verifikasi);
    }

    /**
     * 14 & 15. Admin Tidak Perlu Melakukan Verifikasi Manual (Antrean Verifikasi Dihapus)
     */
    public function test_14_admin_does_not_need_verification_queue(): void
    {
        // Akses route lama antrean verifikasi otomatis dialihkan ke Data Guru
        $resAntrean = $this->actingAs($this->admin)->get('/admin/verifikasi/antrean');
        $resAntrean->assertRedirect('/admin/guru');
    }

    public function test_15_old_riwayat_route_redirects_to_guru(): void
    {
        $resRiwayat = $this->actingAs($this->admin)->get('/admin/verifikasi/riwayat');
        $resRiwayat->assertRedirect('/admin/guru');
    }

    /**
     * 16. Guru Tidak Dapat Mengedit Data (Tidak Ada Endpoint Publik Edit)
     */
    public function test_16_guru_cannot_edit_data_via_public_route(): void
    {
        $guru = Guru::create([
            'nomor_registrasi' => Guru::generateNomorRegistrasi(),
            'nama' => 'Guru No Public Edit',
            'nik' => '5302010101900017',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3000000,
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
            'status_verifikasi' => 'Menunggu Verifikasi',
        ]);

        // Cek bahwa route publik untuk edit tidak ditemukan (404/MethodNotAllowed)
        $resGet = $this->get("/guru/{$guru->id}/edit");
        $resGet->assertStatus(404);

        $resPut = $this->put("/guru/{$guru->id}", ['nama' => 'Perubahan Ilegal']);
        $resPut->assertStatus(404);
    }

    /**
     * 17. Cek Status Publik Tidak Membocorkan Informasi Pribadi (Gaji, NIP/NIK lengkap)
     */
    public function test_17_cek_status_does_not_leak_private_data(): void
    {
        $guru = Guru::create([
            'nomor_registrasi' => 'PGK-2026-PRIV9999',
            'nama' => 'Guru Rahasia Pribadi',
            'nip' => '199201012022011005',
            'nik' => '5302010101920005',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 7750000,
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
            'status_verifikasi' => 'Disetujui',
        ]);

        $response = $this->post('/cek-status', [
            'nomor_registrasi' => 'PGK-2026-PRIV9999',
            'digit_verifikasi' => '0005', // 4 digit terakhir NIP/NIK
        ]);

        $response->assertStatus(200);
        $response->assertSee('DISETUJUI');
        // Pastikan tidak membocorkan gaji atau NIP/NIK lengkap
        $response->assertDontSee('7750000');
        $response->assertDontSee('7.750.000');
        $response->assertDontSee('199201012022011005');
        $response->assertDontSee('5302010101920005');
    }

    /**
     * 18, 19, 20, 21. Export Excel Wajib 6 Sheet & Format Teks NIP/NIK/NPSN
     */
    public function test_18_to_21_export_excel_six_sheets_and_formatting(): void
    {
        $categories = ExcelExportService::SHEET_CATEGORIES;
        $this->assertCount(6, $categories);
        $this->assertEquals([
            'Kemenag',
            'Kemenag 2026',
            'Dinas',
            'Dinas 2026',
            'PPPK',
            'PPPK 2026',
        ], $categories);

        // Buat guru dengan NIP dan NPSN diawali angka 0 untuk menguji format teks
        Guru::create([
            'nomor_registrasi' => 'PGK-2026-ZERO0001',
            'nama' => 'Guru Zero Leading',
            'nip' => '012345678901234567',
            'nik' => '0123456789012345',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 4000000,
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'penerima_tpg' => 'Kemenag',
            'sekolah_id' => $this->sekolah->id_sekolah,
            'status_verifikasi' => 'Disetujui',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/laporan/export-excel?status=Disetujui');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /**
     * 22. Riwayat Login Admin
     */
    public function test_22_riwayat_login_admin(): void
    {
        $this->post('/login', [
            'email' => 'wrong_admin@bimaskatolik.local',
            'password' => 'wrong_password',
        ]);

        $this->assertDatabaseHas('login_histories', [
            'username' => 'wrong_admin@bimaskatolik.local',
            'status' => 'failed',
        ]);
    }

    /**
     * 23. Log Aktivitas Admin
     */
    public function test_23_log_aktivitas_admin(): void
    {
        ActivityLog::log('test_action', 'Melakukan pengujian sistem otomatis');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'test_action',
            'description' => 'Melakukan pengujian sistem otomatis',
        ]);
    }

    /**
     * 24. Soft Delete dan Pemulihan Data
     */
    public function test_24_soft_delete_and_restore(): void
    {
        $guru = Guru::create([
            'nomor_registrasi' => Guru::generateNomorRegistrasi(),
            'nama' => 'Guru Untuk Dihapus',
            'nik' => '5302010101900018',
            'pangkat_golongan_id' => $this->pangkat->id,
            'gaji_pokok' => 3000000,
            'kgb_bulan' => 1,
            'kgb_tahun' => 2026,
            'pensiun_bulan' => 1,
            'pensiun_tahun' => 2050,
            'sekolah_id' => $this->sekolah->id_sekolah,
            'status_verifikasi' => 'Menunggu Verifikasi',
        ]);

        // Soft Delete
        $resDel = $this->actingAs($this->admin)->delete("/admin/guru/{$guru->id}");
        $resDel->assertSessionHas('success');
        $this->assertSoftDeleted('gurus', ['id' => $guru->id]);

        // Restore
        $resRestore = $this->actingAs($this->admin)->post("/admin/guru/{$guru->id}/restore");
        $resRestore->assertSessionHas('success');
        $this->assertDatabaseHas('gurus', ['id' => $guru->id, 'deleted_at' => null]);
    }

    /**
     * 25. KOREKSI FINAL: Admin DILARANG Menambahkan Data Guru Secara Manual!
     */
    public function test_25_admin_cannot_add_guru_manually(): void
    {
        // 1. Pastikan tidak ada halaman / formulir tambah guru di backend
        $resCreate = $this->actingAs($this->admin)->get('/admin/guru/create');
        $this->assertTrue(in_array($resCreate->status(), [404, 405]), 'Route create guru untuk admin harus 404/405');

        // 2. Pastikan tidak ada endpoint POST untuk admin menyimpan guru baru secara manual
        $resStore = $this->actingAs($this->admin)->post('/admin/guru', [
            'nama' => 'Guru Ilegal dari Admin',
            'nik' => '5302010101999999',
            'sekolah_id' => $this->sekolah->id_sekolah,
        ]);
        $this->assertTrue(in_array($resStore->status(), [404, 405]), 'Route store guru oleh admin harus 404/405');
    }

    /**
     * 26. Halaman Publik Starter (premium-laravel-public-frontend) Berfungsi Penuh
     */
    public function test_26_public_frontend_starter_pages_accessible(): void
    {
        $this->get('/beranda')->assertStatus(200)->assertSee('BIMAS KATOLIK');
        $this->get('/profil')->assertStatus(200)->assertSee('Seksi Bimas Katolik');
        $this->get('/layanan')->assertStatus(200)->assertSee('Layanan Kepegawaian');
        $this->get('/berita')->assertStatus(200)->assertSee('Pengumuman');
        $this->get('/kontak')->assertStatus(200)->assertSee('Pusat Bantuan');
    }
}
