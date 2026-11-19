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
        <h1 style="margin: 10px 0 0; font-size: clamp(24px, 3.5vw, 30px); font-weight: 800; letter-spacing: -0.03em; color: var(--pub-text, #0f172a);">
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
        <div class="pd-panel" style="padding: clamp(24px, 4vw, 36px); display: flex; flex-direction: column; gap: 20px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,23,42,0.06);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <span class="pub-badge-year" style="font-size: 10.5px; padding: 4px 12px; background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa;">HASIL VERIFIKASI DATA</span>
                <span style="font-size: 12px; color: #475569; font-family: monospace; font-weight: 600;">{{ $guruResult['tanggal_pengajuan'] }}</span>
            </div>

            <!-- Status Box -->
            <div style="text-align: center; padding: 24px; border-radius: 14px; {{ $guruResult['status_verifikasi'] === 'Disetujui' ? 'background: #f0fdf4; border: 1px solid #bbf7d0;' : ($guruResult['status_verifikasi'] === 'Ditolak' ? 'background: #fef2f2; border: 1px solid #fecaca;' : 'background: #fffbeb; border: 1px solid #fde68a;') }}">
                @if($guruResult['status_verifikasi'] === 'Disetujui')
                    <span style="display: inline-block; padding: 4px 14px; border-radius: 9999px; font-size: 11px; font-weight: 800; background: #dcfce7; color: #15803d; margin-bottom: 8px;">✓ TERVERIFIKASI RESMI</span>
                    <h2 style="margin: 6px 0 0; font-size: 24px; font-weight: 900; color: #15803d;">DISETUJUI</h2>
                    <p style="margin: 8px 0 0; color: #475569; font-size: 13px;">
                        Data pendataan Anda telah diperiksa dan disetujui oleh Administrator Kepegawaian.
                    </p>
                @elseif($guruResult['status_verifikasi'] === 'Ditolak')
                    <span style="display: inline-block; padding: 4px 14px; border-radius: 9999px; font-size: 11px; font-weight: 800; background: #fee2e2; color: #b91c1c; margin-bottom: 8px;">✕ DITOLAK PETUGAS</span>
                    <h2 style="margin: 6px 0 0; font-size: 24px; font-weight: 900; color: #b91c1c;">DITOLAK</h2>
                    <div style="margin-top: 14px; padding: 12px 16px; border-radius: 10px; background: #ffffff; border: 1px solid #fecaca; text-align: left; font-size: 13px;">
                        <strong style="color: #b91c1c; display: block; margin-bottom: 4px;">Catatan Penolakan:</strong>
                        <p style="margin: 0; color: #334155;">{{ $guruResult['alasan_penolakan'] ?: 'Data tidak sesuai atau berkas persyaratan belum lengkap.' }}</p>
                    </div>
                @else
                    <span class="pub-badge-year" style="font-size: 11px; padding: 4px 14px; margin-bottom: 8px; background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">◷ SEDANG DIPROSES</span>
                    <h2 style="margin: 6px 0 0; font-size: 24px; font-weight: 900; color: #b45309;">MENUNGGU VERIFIKASI</h2>
                    <p style="margin: 8px 0 0; color: #475569; font-size: 13px;">
                        Data Anda telah tersimpan di sistem dan sedang dalam antrean verifikasi administrator.
                    </p>
                @endif
            </div>

            <!-- Detail Aman -->
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #475569;">Nomor Registrasi:</span>
                    <strong style="font-family: monospace; color: #ea580c; font-size: 13.5px; font-weight: 800;">{{ $guruResult['nomor_registrasi'] }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #475569;">Nama Guru:</span>
                    <strong style="color: #0f172a; font-size: 13.5px; font-weight: 700;">{{ $guruResult['nama'] }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                    <span style="color: #475569;">Sekolah Tempat Tugas:</span>
                    <strong style="color: #0f172a; font-size: 13.5px; font-weight: 700;">{{ $guruResult['nama_sekolah'] }}</strong>
                </div>
            </div>

            <!-- Tombol Koreksi / Edit Mandiri -->
            <div style="margin-top: 6px; padding-top: 18px; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 10px;">
                <div style="font-size: 12.5px; color: #475569; text-align: center; font-weight: 500;">
                    Ada kesalahan isian data (nama, NIP, NIK, golongan, gaji pokok, sekolah)?
                </div>
                <a href="{{ $guruResult['edit_url'] }}" class="pub-button pub-button-primary pub-button-large" style="width: 100%; height: 46px; font-weight: 800; font-size: 13.5px; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span>✎ PERBAIKI / EDIT DATA FORMULIR INI</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    @endif
</div>
</div>
</section>
@endsection
