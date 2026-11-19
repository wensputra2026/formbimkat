<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicGuruController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SekolahController;
use App\Http\Controllers\Admin\PangkatGolonganController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Public Routes (Guru & Tamu Publik Tanpa Login)
|--------------------------------------------------------------------------
| Pada http://127.0.0.1:8000/ langsung diarahkan ke form isian pendataan guru.
*/
Route::get('/', [PublicGuruController::class, 'create'])->name('guru.create');
Route::post('/registrasi', [PublicGuruController::class, 'store'])->name('guru.store');
Route::get('/registrasi/sukses/{nomor_registrasi}', [PublicGuruController::class, 'sukses'])->name('guru.sukses');

// Cek status pendaftaran mandiri (hanya nomor registrasi + 4 digit verifikasi)
Route::match(['get', 'post'], '/cek-status', [PublicGuruController::class, 'cekStatus'])
    ->middleware('throttle:15,1')
    ->name('guru.cek_status');

// Perbaiki / koreksi formulir data mandiri oleh guru
Route::get('/perbaiki-data/{nomor_registrasi}', [PublicGuruController::class, 'editMandiri'])->name('guru.edit_mandiri');
Route::put('/perbaiki-data/{nomor_registrasi}', [PublicGuruController::class, 'updateMandiri'])->name('guru.update_mandiri');

// AJAX ketersediaan NIP/NIK & pencarian sekolah publik
Route::post('/guru/check-duplicate', [PublicGuruController::class, 'checkDuplicate'])
    ->middleware('throttle:30,1')
    ->name('guru.check_duplicate');
Route::get('/sekolah/cari', [PublicGuruController::class, 'cariSekolah'])->name('sekolah.cari');

/*
|--------------------------------------------------------------------------
| Public Frontend Information Pages (Premium Institutional Starter)
|--------------------------------------------------------------------------
*/
Route::view('/beranda', 'public.home')->name('public.home');
Route::view('/profil', 'public.about')->name('public.about');
Route::view('/layanan', 'public.services')->name('public.services');
Route::view('/berita', 'public.news')->name('public.news');
Route::view('/kontak', 'public.contact')->name('public.contact');

/*
|--------------------------------------------------------------------------
| Autentikasi Admin (Satu Level User Saja: Admin)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Backend Admin (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Data Guru
    // CATATAN: TIDAK ADA ROUTE CREATE / STORE MANUAL ADMIN!
    // Seluruh data hanya berasal dari formulir pendataan publik.
    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/guru/export-excel', [GuruController::class, 'exportExcel'])->name('guru.export_excel');
    Route::get('/guru/{id}', [GuruController::class, 'show'])->name('guru.show');
    Route::get('/guru/{guru}/edit', [GuruController::class, 'edit'])->name('guru.edit');
    Route::put('/guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
    Route::post('/guru/{guru}/verify', [GuruController::class, 'verify'])->name('guru.verify');
    Route::delete('/guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
    Route::post('/guru/{id}/restore', [GuruController::class, 'restore'])->name('guru.restore');

    // 3. Verifikasi Data DIHAPUS (Admin tidak perlu verifikasi)
    // Redirect URL lama verifikasi ke Data Guru
    Route::any('/verifikasi/{any?}', function () {
        return redirect()->route('admin.guru.index');
    })->where('any', '.*')->name('verifikasi.antrean');

    // 3. Master Sekolah
    Route::get('/sekolah', [SekolahController::class, 'index'])->name('sekolah.index');
    Route::post('/sekolah/sync', [SekolahController::class, 'sync'])->name('sekolah.sync');

    // 5. Master Pangkat / Golongan
    Route::get('/pangkat', [PangkatGolonganController::class, 'index'])->name('pangkat.index');
    Route::post('/pangkat', [PangkatGolonganController::class, 'store'])->name('pangkat.store');
    Route::put('/pangkat/{pangkatGolongan}', [PangkatGolonganController::class, 'update'])->name('pangkat.update');
    Route::delete('/pangkat/{pangkatGolongan}', [PangkatGolonganController::class, 'destroy'])->name('pangkat.destroy');

    // 6. Laporan & Export
    Route::get('/laporan', [ReportController::class, 'index'])->name('report.index');
    Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('report.export_excel');
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('report.export_pdf');

    // 7. Audit: Riwayat Login & Log Aktivitas
    Route::get('/riwayat-login', [AuditController::class, 'loginHistories'])->name('audit.login');
    Route::get('/log-aktivitas', [AuditController::class, 'activityLogs'])->name('audit.activity');

    // 8. Pengaturan Aplikasi & Backup/Restore
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/pengaturan', [SettingController::class, 'update'])->name('setting.update');
    Route::get('/pengaturan/backup', [SettingController::class, 'backupDatabase'])->name('setting.backup');
    Route::post('/pengaturan/restore', [SettingController::class, 'restoreDatabase'])->name('setting.restore');
});
