@extends('layouts.public')

@section('title', 'Cek Status Pengajuan Guru')

@section('content')
<section class="pub-section" style="padding-top: 35px; padding-bottom: 60px;">
<div class="pub-container">
<div style="max-width: 580px; width: 100%; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <!-- Heading -->
    <div style="text-align: center;">
        <span class="pub-badge-year">
            <i></i> VERIFIKASI IDENTITAS & STATUS
        </span>
        <h1 style="margin: 10px 0 0; font-size: clamp(24px, 3.5vw, 30px); font-weight: 800; letter-spacing: -0.03em; color: #ffffff;">
            Cek Status Pendataan Guru<span style="color: var(--pub-accent);">.</span>
        </h1>
        <p style="margin: 8px 0 0; color: var(--pub-muted); font-size: 13.5px;">
            Masukkan Nomor Registrasi dan 4 digit terakhir NIP atau NIK Anda.
        </p>
    </div>

    <!-- Panel Form -->
    <div class="pd-panel" style="padding: clamp(24px, 4vw, 36px);">
        <form action="{{ route('guru.cek_status') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf

            <!-- Nomor Registrasi -->
            <div>
                <label for="nomor_registrasi" class="pd-label">Nomor Registrasi <em>*</em></label>
                <input type="text" name="nomor_registrasi" id="nomor_registrasi" value="{{ old('nomor_registrasi') }}" required placeholder="Contoh: PGK-2026-XXXXXXXX" class="pd-input" style="font-family: monospace; letter-spacing: 0.05em; text-transform: uppercase;">
                <span class="pd-hint">Nomor registrasi yang diperoleh saat formulir dikirim.</span>
                @error('nomor_registrasi') <p style="color: #ef4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            <!-- 4 Digit Terakhir NIP / NIK -->
            <div>
                <label for="digit_verifikasi" class="pd-label">4 Digit Terakhir NIP atau NIK <em>*</em></label>
                <input type="text" inputmode="numeric" maxlength="4" name="digit_verifikasi" id="digit_verifikasi" value="{{ old('digit_verifikasi') }}" required placeholder="Contoh: 1234" class="pd-input" style="font-family: monospace; letter-spacing: 0.1em;">
                <span class="pd-hint">Pengaman privasi agar data Anda tidak dilihat oleh orang lain.</span>
                @error('digit_verifikasi') <p style="color: #ef4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="pub-button pub-button-primary pub-button-large" style="width: 100%; font-weight: 800; font-size: 13.5px; margin-top: 6px;">
                <span>PERIKSA STATUS PENGAJUAN</span>
                <span>→</span>
            </button>
        </form>
    </div>

    <!-- Alert Error Jika Tidak Cocok -->
    @if(isset($error) && $error)
        <div class="pd-alert pd-alert-error" style="border-radius: 12px; font-size: 13px;">
            <strong style="display: block; margin-bottom: 2px;">Pemeriksaan Gagal</strong>
            <span>{{ $error }}</span>
        </div>
    @endif

    <!-- Hasil Status Publik -->
    @if(isset($guruResult) && $guruResult)
        <div class="pd-panel" style="padding: clamp(24px, 4vw, 36px); display: flex; flex-direction: column; gap: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--pub-line); padding-bottom: 12px;">
                <span class="pub-badge-year" style="font-size: 10px; padding: 3px 10px;">HASIL VERIFIKASI DATA</span>
                <span style="font-size: 12px; color: var(--pub-muted); font-family: monospace;">{{ $guruResult['tanggal_pengajuan'] }}</span>
            </div>

            <!-- Status Box -->
            <div style="text-align: center; padding: 24px; border-radius: 14px; {{ $guruResult['status_verifikasi'] === 'Disetujui' ? 'background: rgba(69,198,154,.1); border: 1px solid rgba(69,198,154,.3);' : ($guruResult['status_verifikasi'] === 'Ditolak' ? 'background: rgba(248,113,113,.1); border: 1px solid rgba(248,113,113,.3);' : 'background: rgba(245,154,69,.1); border: 1px solid rgba(245,154,69,.3);') }}">
                @if($guruResult['status_verifikasi'] === 'Disetujui')
                    <span style="display: inline-block; padding: 4px 14px; border-radius: 9999px; font-size: 11px; font-weight: 800; background: rgba(69,198,154,.18); color: #45c69a; margin-bottom: 8px;">✓ TERVERIFIKASI RESMI</span>
                    <h2 style="margin: 6px 0 0; font-size: 24px; font-weight: 900; color: #45c69a;">DISETUJUI</h2>
                    <p style="margin: 8px 0 0; color: #94a3b8; font-size: 13px;">
                        Data pendataan Anda telah diperiksa dan disetujui oleh Administrator Kepegawaian.
                    </p>
                @elseif($guruResult['status_verifikasi'] === 'Ditolak')
                    <span style="display: inline-block; padding: 4px 14px; border-radius: 9999px; font-size: 11px; font-weight: 800; background: rgba(248,113,113,.18); color: #f87171; margin-bottom: 8px;">✕ DITOLAK PETUGAS</span>
                    <h2 style="margin: 6px 0 0; font-size: 24px; font-weight: 900; color: #f87171;">DITOLAK</h2>
                    <div style="margin-top: 14px; padding: 12px 16px; border-radius: 10px; background: rgba(248,113,113,.08); border: 1px solid rgba(248,113,113,.25); text-align: left; font-size: 13px;">
                        <strong style="color: #f87171; display: block; margin-bottom: 4px;">Catatan Penolakan:</strong>
                        <p style="margin: 0; color: #cbd5e1;">{{ $guruResult['alasan_penolakan'] ?: 'Data tidak sesuai atau berkas persyaratan belum lengkap.' }}</p>
                    </div>
                @else
                    <span class="pub-badge-year" style="font-size: 11px; padding: 4px 14px; margin-bottom: 8px;">◷ SEDANG DIPROSES</span>
                    <h2 style="margin: 6px 0 0; font-size: 24px; font-weight: 900; color: #ffbd78;">MENUNGGU VERIFIKASI</h2>
                    <p style="margin: 8px 0 0; color: #94a3b8; font-size: 13px;">
                        Data Anda telah tersimpan di sistem dan sedang dalam antrean verifikasi administrator.
                    </p>
                @endif
            </div>

            <!-- Detail Aman -->
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--pub-line);">
                    <span style="color: #94a3b8;">Nomor Registrasi:</span>
                    <strong style="font-family: monospace; color: #ffbd78;">{{ $guruResult['nomor_registrasi'] }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                    <span style="color: #94a3b8;">Sekolah Tempat Tugas:</span>
                    <strong style="color: #ffffff;">{{ $guruResult['nama_sekolah'] }}</strong>
                </div>
            </div>
        </div>
    @endif
</div>
</div>
</section>
@endsection
