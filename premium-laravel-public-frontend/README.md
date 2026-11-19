# Premium Laravel Public Frontend Starter

Starter frontend/public untuk Laravel 12/13:
- Blade + Tailwind CSS 3 + Vite
- Alpine.js untuk navigasi mobile, dropdown, dan Light/Dark Mode
- Modern Institutional design
- Responsif desktop, tablet, dan ponsel
- Tanpa ketergantungan foto besar; hero menggunakan CSS
- Halaman: Beranda, Profil, Layanan, Berita, Kontak

Paket ini adalah template frontend siap integrasi ke proyek Laravel yang sudah dibuat. Ini bukan instalasi Laravel utuh dan tidak mengubah autentikasi, database, atau dashboard admin yang sudah ada.

## 1. Prasyarat

- Laravel 12 atau 13
- Node.js dan npm
- Tailwind CSS 3 + Vite
- Alpine.js

## 2. Instalasi

1. Ekstrak ZIP.
2. Salin folder `resources/views/public` dan `resources/views/layouts/public.blade.php` ke proyek.
3. Salin `resources/css/public.css` ke proyek.
4. Gabungkan `resources/js/public.js` ke `resources/js/app.js`.
5. Tambahkan route dari `routes/public-frontend.example.php` ke `routes/web.php`.
6. Tambahkan CSS ke `resources/css/app.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@import './public.css';
```

7. Pastikan `resources/js/app.js` menginisialisasi Alpine satu kali:

```js
import './bootstrap';
import Alpine from 'alpinejs';
import './public.js';

window.Alpine = Alpine;
Alpine.start();
```

Jika proyek sudah menginisialisasi Alpine, jangan memanggil `Alpine.start()` untuk kedua kalinya. Cukup gabungkan komponen `Alpine.data` dari `public.js` ke konfigurasi Alpine yang sudah ada.

8. Pastikan `vite.config.js` memuat `resources/css/app.css` dan `resources/js/app.js`.
9. Jalankan:

```bash
npm install
npm run build
php artisan view:clear
php artisan route:clear
```

Untuk pengembangan gunakan `npm run dev`.

## 3. Route dan halaman

Contoh route menyediakan:
- `/` → `public.home`
- `/profil` → `public.about`
- `/layanan` → `public.services`
- `/berita` → `public.news`
- `/kontak` → `public.contact`

Route contoh memakai `Route::view` dan konten statis agar template langsung dapat dilihat. Untuk aplikasi produksi, ganti konten contoh dengan data dari Controller, Model, dan database.

## 4. Identitas website

Ubah nama, tagline, alamat, email, telepon, dan tautan sosial pada:
- `resources/views/partials/public-navbar.blade.php`
- `resources/views/partials/public-footer.blade.php`
- `resources/views/public/contact.blade.php`

Ubah logo teks "INSTITUSI" sesuai nama aplikasi/lembaga.

## 5. Light/Dark Mode

Tombol tema berada di navbar dan footer. Pilihan tema disimpan di `localStorage` browser. Tema default adalah Light Mode dan akan mengikuti pilihan terakhir pengunjung.

## 6. Integrasi dengan dashboard admin

Frontend publik memakai layout `layouts.public`, sedangkan dashboard admin tetap memakai layout `layouts.app` dari Premium Dashboard Starter.

Keduanya dapat hidup berdampingan:
- `layouts.public` untuk halaman tanpa login.
- `layouts.app` untuk dashboard setelah login.

Jangan mengganti atau menimpa layout dashboard admin saat memasang frontend ini.

## 7. Produksi dan keamanan

- Lindungi route admin dengan middleware `auth` dan middleware role/permission.
- Form kontak pada contoh ini adalah tampilan UI. Hubungkan ke Controller, validasi, rate limiting, dan penyimpanan/pengiriman email sebelum digunakan untuk menerima pesan sungguhan.
- Ganti tautan `#` dengan route yang benar.
- Tambahkan metadata SEO spesifik setiap halaman.
- Jika memakai CMS atau database, kirim data dari Controller; hindari query langsung di Blade.
- Periksa ulang warna, logo, konten, dan tautan sebelum deployment.

## 8. Integrasi bersama Dashboard Starter

Gunakan dua ZIP sebagai satu fondasi:
1. `premium-laravel-dashboard-starter.zip` untuk backend/admin.
2. `premium-laravel-public-frontend-starter.zip` untuk frontend/public.

Keduanya berbagi stack Laravel, Blade, Tailwind, Alpine, dan Vite. Gabungkan file dengan hati-hati; jangan menimpa `app.js`, `app.css`, atau `web.php` yang telah berisi konfigurasi proyek.
