<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
| Gabungkan route berikut ke routes/web.php.
| Jika nama route sudah digunakan aplikasi, sesuaikan nama route-nya.
*/

Route::view('/', 'public.home')->name('public.home');
Route::view('/profil', 'public.about')->name('public.about');
Route::view('/layanan', 'public.services')->name('public.services');
Route::view('/berita', 'public.news')->name('public.news');
Route::view('/kontak', 'public.contact')->name('public.contact');
