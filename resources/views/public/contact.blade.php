@extends('layouts.public')
@section('title', 'Kontak Pelayanan')
@section('meta_description', 'Kontak resmi Seksi Bimbingan Masyarakat Katolik Kantor Kementerian Agama Kab. Timor Tengah Selatan.')

@section('content')
<section class="pub-page-hero">
    <div class="pub-container">
        <div class="pub-eyebrow">HUBUNGI KAMI</div>
        <h1>Pusat Bantuan & <em>Konsultasi Guru.</em></h1>
        <p>Hubungi Seksi Bimas Katolik untuk kendala teknis pengisian formulir atau pertanyaan verifikasi.</p>
    </div>
</section>

<section class="pub-section">
    <div class="pub-container pub-contact-grid">
        <div class="pub-contact-info">
            <div class="pub-eyebrow">INFORMASI RESMI</div>
            <h2>Kantor Seksi Bimas Katolik<br>Kemenag Kab. TTS</h2>
            <p>Silakan berkunjung atau hubungi kami melalui saluran resmi berikut pada hari dan jam kerja dinas.</p>

            <div class="pub-contact-item">
                <span>⌖</span>
                <div>
                    <small>ALAMAT KANTOR</small>
                    <strong>Jl. Soe - Kefa, Soe, Kab. Timor Tengah Selatan, NTT, 85511</strong>
                </div>
            </div>
            <div class="pub-contact-item">
                <span>✉</span>
                <div>
                    <small>EMAIL RESMI</small>
                    <strong>{{ \App\Models\Setting::get('email_resmi', 'bimaskatolik.tts@kemenag.go.id') }}</strong>
                </div>
            </div>
            <div class="pub-contact-item">
                <span>☏</span>
                <div>
                    <small>TELEPON / LAYANAN</small>
                    <strong>{{ \App\Models\Setting::get('telepon_resmi', '(0388) 123456') }}</strong>
                </div>
            </div>
            <div class="pub-contact-item">
                <span>◷</span>
                <div>
                    <small>JAM OPERASIONAL</small>
                    <strong>Senin – Jumat, 08.00 – 16.00 WITA</strong>
                </div>
            </div>
        </div>

        <div class="pub-form-card" style="padding: clamp(24px, 4vw, 36px); background: var(--pub-card); border: 1px solid var(--pub-line); border-radius: 20px;">
            <h3 style="margin-top: 0; font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 800; color: var(--pub-text);">Konsultasi Pendataan</h3>
            <p style="font-size: 13px; color: var(--pub-muted); margin-bottom: 20px; line-height: 1.6;">
                Apabila Anda mengalami kendala teknis pengisian (misal: sekolah tidak ditemukan atau salah ketik NIP/NIK), informasikan kepada kami:
            </p>
            
            <div style="display: flex; flex-direction: column; gap: 14px; font-size: 13px;">
                <div style="padding: 14px 18px; border-radius: 12px; background: var(--pub-bg-soft); border: 1px solid var(--pub-line);">
                    <strong style="color: var(--pub-text); display: block; margin-bottom: 4px;">Pencarian Sekolah</strong>
                    <span style="color: var(--pub-muted);">Ketik minimal 2 huruf nama sekolah atau kecamatan pada kolom pencarian sekolah di formulir.</span>
                </div>
                <div style="padding: 14px 18px; border-radius: 12px; background: var(--pub-bg-soft); border: 1px solid var(--pub-line);">
                    <strong style="color: var(--pub-text); display: block; margin-bottom: 4px;">Perbaikan Data Setelah Submit</strong>
                    <span style="color: var(--pub-muted);">Guru tidak dapat mengedit langsung setelah terkirim. Laporkan nomor registrasi Anda ke Admin untuk dikoreksi.</span>
                </div>
                <div style="padding: 14px 18px; border-radius: 12px; background: var(--pub-bg-soft); border: 1px solid var(--pub-line);">
                    <strong style="color: var(--pub-text); display: block; margin-bottom: 4px;">Pemeriksaan Status</strong>
                    <span style="color: var(--pub-muted);">Gunakan menu <a href="{{ route('guru.cek_status') }}" style="color: var(--pub-accent); font-weight: 700; text-decoration: underline;">Cek Status</a> dengan 4 digit terakhir NIP atau NIK Anda.</span>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <a href="{{ route('guru.create') }}" class="pub-button pub-button-primary pub-button-large" style="width: 100%;">
                    Buka Formulir Pendataan Guru <span>→</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
