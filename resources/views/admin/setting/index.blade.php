@extends('layouts.admin')

@section('title', 'Pengaturan Aplikasi & Backup')
@section('page_title', 'Pengaturan Aplikasi')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px; width: 100%; max-width: none; margin: 0;">

    <!-- Heading -->
    <div class="pd-page-heading" style="margin-bottom: 8px;">
        <div>
            <div class="pd-eyebrow">KONFIGURASI SISTEM & PEMELIHARAAN</div>
            <h1>Pengaturan &amp; Cadangan Database<span class="pd-heading-accent">.</span></h1>
            <p>Kelola identitas instansi, tahun aktif pendataan guru, pengumuman publik, serta pencadangan database.</p>
        </div>
    </div>

    <!-- Panel Pengaturan Identitas Aplikasi -->
    <div class="pd-panel" style="padding: 24px;">
        <div class="pd-panel-heading">
            <div>
                <h2>Informasi Identitas & Instansi</h2>
                <p>Data ini digunakan pada kop formulir publik, laporan PDF, dan ekspor dokumen resmi</p>
            </div>
        </div>

        <form action="{{ route('admin.setting.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 18px; margin-top: 14px;">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                <div>
                    <label class="pd-label">Nama Aplikasi <em>*</em></label>
                    <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'Pendataan Guru.') }}" required class="pd-input">
                </div>

                <div>
                    <label class="pd-label">Nama Instansi / Satuan Kerja <em>*</em></label>
                    <input type="text" name="instansi" value="{{ old('instansi', $settings['instansi'] ?? 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}" required class="pd-input">
                </div>

                <div>
                    <label class="pd-label">Tahun Pendataan Aktif <em>*</em></label>
                    <input type="text" name="tahun_pendataan" value="{{ old('tahun_pendataan', $settings['tahun_pendataan'] ?? date('Y')) }}" required class="pd-input">
                </div>

                <div>
                    <label class="pd-label">Email Kontak Layanan</label>
                    <input type="email" name="email" value="{{ old('email', $settings['email'] ?? 'pendataan@kemenag.go.id') }}" class="pd-input">
                </div>

                <div>
                    <label class="pd-label">Nomor Kontak / WhatsApp Helpdesk</label>
                    <input type="text" name="kontak" value="{{ old('kontak', $settings['kontak'] ?? '') }}" placeholder="Contoh: 081234567890" class="pd-input">
                </div>

                <div>
                    <label class="pd-label">Alamat Kantor / Satker</label>
                    <input type="text" name="alamat_instansi" value="{{ old('alamat_instansi', $settings['alamat_instansi'] ?? '') }}" placeholder="Contoh: Jl. Lapangan Banteng Barat No. 3-4, Jakarta Pusat" class="pd-input">
                </div>
            </div>

            <div>
                <label class="pd-label">Pengumuman / Petunjuk Pengisian Formulir Guru Publik</label>
                <textarea name="pengumuman" rows="4" class="pd-textarea" placeholder="Tuliskan petunjuk atau pengumuman yang akan ditampilkan pada halaman depan pengisian formulir guru...">{{ old('pengumuman', $settings['pengumuman'] ?? '') }}</textarea>
                <span class="pd-hint">Teks ini langsung muncul pada kartu pengumuman di halaman depan formulir guru mandiri.</span>
            </div>

            <div style="display: flex; justify-content: flex-end; padding-top: 10px;">
                <button type="submit" class="pd-button pd-button-primary" style="height: 40px; padding: 0 20px;">
                    <span>Simpan Perubahan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Panel Cadangan & Pemulihan Database (Backup / Restore) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px;">
        <!-- Backup Database -->
        <div class="pd-panel" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div class="pd-panel-heading">
                    <div>
                        <h2>Cadangkan Database (Backup)</h2>
                        <p>Unduh salinan penuh seluruh tabel dan data aplikasi ke file format SQL</p>
                    </div>
                </div>
                <p style="font-size: 11px; color: var(--pd-muted); line-height: 1.6; margin: 12px 0 20px;">
                    Pencadangan mencakup seluruh data guru terdaftar, riwayat verifikasi, master sekolah BIMKAT, master pangkat/golongan, riwayat login, dan log aktivitas keamanan.
                </p>
            </div>

            <a href="{{ route('admin.setting.backup') }}" class="pd-button pd-button-primary" style="width: 100%; height: 42px;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>UNDUH CADANGAN DATABASE (.SQL)</span>
            </a>
        </div>

        <!-- Restore Database -->
        <div class="pd-panel" style="padding: 24px;">
            <div class="pd-panel-heading">
                <div>
                    <h2 style="color: #ffd280;">Pemulihan Database (Restore)</h2>
                    <p>Unggah file SQL cadangan untuk memulihkan seluruh struktur dan data</p>
                </div>
            </div>

            <form action="{{ route('admin.setting.restore') }}" method="POST" enctype="multipart/form-data" data-swal-title="Pulihkan database?" data-swal-confirm="PERINGATAN: seluruh data saat ini akan ditimpa isi file cadangan." data-swal-confirm-button="Ya, pulihkan" style="display: flex; flex-direction: column; gap: 14px; margin-top: 12px;">
                @csrf
                <div>
                    <label class="pd-label">Pilih File SQL Cadangan <em>*</em></label>
                    <input type="file" name="backup_file" accept=".sql" required class="pd-input" style="padding: 8px 12px;">
                    <span class="pd-hint">Maksimal ukuran file 50 MB, format murni .sql.</span>
                </div>

                <div style="padding: 10px 12px; border-radius: 8px; background: rgba(239,68,68,.08); border: 1px dashed rgba(239,68,68,.25); font-size: 10px; color: #ffaaa4; line-height: 1.5;">
                    ⚠️ Tindakan ini berisiko tinggi. Pastikan file cadangan berasal dari sistem ini.
                </div>

                <button type="submit" class="pd-button pd-button-warning" style="height: 42px; width: 100%;">
                    <span>PULIHKAN DATABASE SEKARANG</span>
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
