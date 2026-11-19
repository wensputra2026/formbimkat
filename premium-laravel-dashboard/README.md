# Premium Laravel Dashboard Starter

Template dashboard reusable untuk aplikasi Laravel 12/13 dengan:
- Blade sebagai view engine
- Tailwind CSS 3 + Vite
- Alpine.js untuk interaksi
- Grouped Menu sebagai sidebar utama
- Icon Rail sebagai mode ringkas
- Dual Pane untuk submenu bertingkat
- Dark Navy / Charcoal dengan aksen Orange-Gold
- Layout responsif desktop, tablet, dan mobile

## Isi paket

```text
app/
  config/
    premium-navigation.php
resources/
  css/
    premium-dashboard.css
  js/
    app.js
  views/
    layouts/
      app.blade.php
    partials/
      sidebar.blade.php
      topbar.blade.php
    dashboard/
      index.blade.php
routes/
  premium-dashboard.example.php
```

> Paket ini adalah lapisan UI siap integrasi, bukan salinan penuh Laravel. Salin file ke proyek Laravel yang sudah dibuat. File autentikasi, model, database, dan kebijakan akses tetap mengikuti aplikasi masing-masing.

## 1. Prasyarat

- Laravel 12 atau 13
- PHP sesuai persyaratan versi Laravel yang dipakai
- Node.js dan npm
- Tailwind CSS 3
- Alpine.js

Jika proyek sudah menggunakan Tailwind CSS dan Vite, jangan menimpa konfigurasi yang sudah ada. Gabungkan bagian yang diperlukan.

## 2. Instalasi

1. Ekstrak paket.
2. Salin folder `config/` ke root proyek Laravel.
3. Salin folder `resources/` ke proyek Laravel.
4. Salin `routes/premium-dashboard.example.php` ke `routes/web.php`, atau gabungkan route dashboard ke route yang sudah ada.
5. Instal dependensi interaksi jika belum ada: `npm install alpinejs @alpinejs/collapse`.
6. Pastikan `resources/css/app.css` memuat CSS dashboard dan Tailwind:

```css
@import './premium-dashboard.css';

@tailwind base;
@tailwind components;
@tailwind utilities;
```

7. Pastikan `resources/js/app.js` memuat Alpine:

```js
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
```

Jika `app.js` proyek sudah memiliki Alpine, jangan inisialisasi Alpine dua kali. Cukup gabungkan komponen yang diperlukan.

8. Pastikan `vite.config.js` memuat entry `resources/css/app.css` dan `resources/js/app.js`.
9. Jalankan:

```bash
npm install
npm run build
php artisan config:clear
php artisan view:clear
```

Untuk pengembangan gunakan `npm run dev`.

## 3. Konfigurasi menu

Edit `config/premium-navigation.php`. Menu mendukung:
- `section`: label grup menu
- `items`: daftar menu
- `children`: submenu
- `route`: nama route Laravel
- `url`: URL langsung
- `icon`: nama ikon Lucide (dipetakan melalui helper SVG di partial)
- `roles`: daftar role yang boleh melihat menu (opsional)

Contoh item:

```php
[
    'label' => 'Data Guru',
    'route' => 'guru.index',
    'icon' => 'users',
    'roles' => ['SUPER_ADMIN', 'ADMIN_GURU'],
]
```

Jika properti `roles` tidak diisi, menu ditampilkan untuk semua pengguna yang sudah melewati middleware autentikasi. Sesuaikan pemeriksaan role dengan sistem role aplikasi Anda. Template menyediakan pemeriksaan sederhana untuk Spatie Permission dan method `hasRole()` bila tersedia; sesuaikan bila memakai sistem otorisasi lain.

## 4. Mengubah identitas aplikasi

Ubah `config/premium-navigation.php`:
- `brand.name`
- `brand.short`
- `brand.description`

Ubah warna di `resources/css/premium-dashboard.css` pada variabel `:root`:
- `--pd-bg`
- `--pd-sidebar`
- `--pd-accent`
- `--pd-accent-2`

## 5. Pola layout

- Desktop: sidebar grouped menu di kiri, konten di kanan.
- Icon Rail: tombol di topbar untuk mengecilkan sidebar menjadi ikon.
- Mobile: sidebar menjadi drawer dengan backdrop.
- Submenu: dapat dibuka/tutup.
- Dual Pane: gunakan untuk modul besar; panel kategori dan submenu dapat dibuat sebagai halaman/modul tersendiri.

## 6. Integrasi keamanan

Tampilan menu bukan pengganti otorisasi. Lindungi setiap route dengan middleware `auth`, middleware role/permission, Policy, atau Gate. Jangan mengandalkan menu tersembunyi untuk mengamankan data.

## 7. Catatan integrasi

- Route contoh menggunakan nama route `dashboard`.
- Ganti kartu statistik dan aktivitas demo dengan data aplikasi.
- Jangan menaruh query database langsung di Blade; siapkan data di controller.
- Gunakan pagination untuk tabel besar.
- Untuk aplikasi yang memakai Laravel Breeze/Jetstream, pertahankan autentikasi yang ada dan gunakan layout ini sebagai layout dashboard setelah login.
