<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Contoh route dashboard
|--------------------------------------------------------------------------
| Gabungkan ke routes/web.php. Tambahkan middleware sesuai autentikasi
| dan otorisasi yang dipakai oleh aplikasi Anda.
*/

Route::middleware(['web', 'auth'])->group(function () {
    Route::view('/dashboard', 'dashboard.index')->name('dashboard');
});
