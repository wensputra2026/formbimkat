@extends('layouts.public')

@section('title', 'Bukti Registrasi Pendataan Guru')

@section('content')
<section class="pub-section" style="padding-top: 35px; padding-bottom: 60px;">
<div class="pub-container">
<div style="max-width: 680px; width: 100%; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">

    <!-- Kartu Bukti -->
    <div class="pd-panel" style="padding: clamp(24px, 5vw, 44px); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 24px;">
        
        <div style="width: 64px; height: 64px; border-radius: 20px; background: rgba(34,197,94,.15); color: #16a34a; display: grid; place-items: center; font-size: 28px; box-shadow: 0 0 30px rgba(34,197,94,.2);">
            ✓
        </div>

        <div>
            <span class="pub-badge-year" style="font-size: 11px; padding: 4px 14px; margin-bottom: 8px;">
                FORMULIR BERHASIL TERKIRIM
            </span>
            <h1 style="margin: 10px 0 0; font-size: clamp(22px, 3.5vw, 28px); font-weight: 800; letter-spacing: -0.03em; color: #ffffff;">
                Bukti Pengajuan Pendataan Guru
            </h1>
            <p style="margin: 6px 0 0; color: var(--pub-muted); font-size: 13.5px;">
                Data Anda telah berhasil dicatat ke dalam database kementerian.
            </p>
        </div>

        <!-- Box Nomor Registrasi Utama -->
        <div style="width: 100%; padding: 22px; border-radius: 16px; background: linear-gradient(135deg, rgba(245,154,69,.15), rgba(245,154,69,.05)); border: 2px dashed rgba(245,154,69,.4);">
            <span style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; color: #ffbd78;">
                Nomor Registrasi Resmi
            </span>
            <div style="font-size: clamp(24px, 4vw, 34px); font-weight: 900; font-family: monospace; letter-spacing: 0.1em; color: #ffbd78; margin: 8px 0; user-select: all;">
                {{ $guru->nomor_registrasi }}
            </div>
            <span style="font-size: 12px; color: var(--pub-muted);">
                Diterbitkan pada {{ $guru->created_at->format('d/m/Y H:i') }} WITA
            </span>
        </div>

        <!-- Rincian Data Pengajuan -->
        <div style="width: 100%; border: 1px solid var(--pub-line); border-radius: 14px; background: var(--pub-bg-soft); overflow: hidden; text-align: left;">
            <div style="padding: 12px 18px; border-bottom: 1px solid var(--pub-line); display: flex; justify-content: space-between; font-size: 13px;">
                <span style="color: var(--pub-muted);">Nama Lengkap:</span>
                <strong style="color: var(--pub-text);">{{ $guru->nama }}</strong>
            </div>
            <div style="padding: 12px 18px; border-bottom: 1px solid var(--pub-line); display: flex; justify-content: space-between; font-size: 13px;">
                <span style="color: var(--pub-muted);">NIP:</span>
                <span style="font-family: monospace; color: var(--pub-text); font-weight: 600;">{{ $guru->nip ?: '-' }}</span>
            </div>
            <div style="padding: 12px 18px; border-bottom: 1px solid var(--pub-line); display: flex; justify-content: space-between; font-size: 13px;">
                <span style="color: var(--pub-muted);">NIK:</span>
                <span style="font-family: monospace; color: var(--pub-text); font-weight: 600;">{{ $guru->nik ?: '-' }}</span>
            </div>
            <div style="padding: 12px 18px; border-bottom: 1px solid var(--pub-line); display: flex; justify-content: space-between; font-size: 13px;">
                <span style="color: var(--pub-muted);">Sekolah Tempat Tugas:</span>
                <strong style="color: var(--pub-text);">{{ $guru->sekolah ? $guru->sekolah->nama_sekolah : '-' }}</strong>
            </div>
            <div style="padding: 12px 18px; border-bottom: 1px solid var(--pub-line); display: flex; justify-content: space-between; font-size: 13px;">
                <span style="color: var(--pub-muted);">Pangkat / Golongan:</span>
                <span style="color: var(--pub-text); font-weight: 600;">{{ $guru->pangkatGolongan ? $guru->pangkatGolongan->label : '-' }}</span>
            </div>
            <div style="padding: 12px 18px; display: flex; justify-content: space-between; font-size: 13px;">
                <span style="color: var(--pub-muted);">Status Verifikasi Saat Ini:</span>
                <span class="pub-badge-year" style="font-size: 11px;"><i></i> {{ $guru->status_verifikasi }}</span>
            </div>
        </div>

        <!-- Instruksi -->
        <div style="padding: 16px 20px; border-radius: 12px; background: var(--pub-card); border: 1px solid var(--pub-line); text-align: left; font-size: 12.5px; color: var(--pub-muted); line-height: 1.6;">
            <strong style="color: var(--pub-text); display: block; margin-bottom: 4px;">Penting:</strong>
            Simpan Nomor Registrasi di atas. Anda dapat memeriksa status persetujuan data kapan saja melalui menu <strong>Cek Status</strong> dengan menginput nomor registrasi dan 4 digit terakhir NIP/NIK Anda.
        </div>

        <!-- Tombol Aksi -->
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 14px; width: 100%;">
            <button type="button" onclick="window.print()" class="pub-button pub-button-outline" style="min-height: 44px;">
                <span>🖨 Cetak Bukti Pendaftaran</span>
            </button>
            <a href="{{ route('guru.cek_status') }}" class="pub-button pub-button-primary" style="min-height: 44px;">
                <span>Cek Status Pengajuan →</span>
            </a>
        </div>
    </div>
</div>
</div>
</section>
@endsection

