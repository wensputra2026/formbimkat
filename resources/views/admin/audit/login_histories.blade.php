@extends('layouts.admin')

@section('title', 'Riwayat Login Admin')
@section('page_title', 'Riwayat Login')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Heading -->
    <div class="pd-page-heading" style="margin-bottom: 8px;">
        <div>
            <div class="pd-eyebrow">AUDIT KEAMANAN & AKSES</div>
            <h1>Riwayat Login Administrator<span class="pd-heading-accent">.</span></h1>
            <p>Catatan log autentikasi, alamat IP, browser, status keberhasilan, dan kegagalan login akun Administrator.</p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="pd-stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <article class="pd-stat-card">
            <div class="pd-stat-top">
                <span>TOTAL PERCOBAAN LOGIN</span>
                <span class="pd-stat-icon">🔑</span>
            </div>
            <div class="pd-stat-value">{{ number_format($totalAttempts) }}</div>
            <div class="pd-stat-bottom">
                <span>Catatan audit autentikasi</span>
            </div>
        </article>

        <article class="pd-stat-card" style="border-color: rgba(69,198,154,.25);">
            <div class="pd-stat-top">
                <span style="color: var(--pd-success);">LOGIN BERHASIL</span>
                <span class="pd-stat-icon" style="background: rgba(69,198,154,.15); color: var(--pd-success);">✓</span>
            </div>
            <div class="pd-stat-value" style="color: var(--pd-success);">{{ number_format($successfulAttempts) }}</div>
            <div class="pd-stat-bottom">
                <strong>Sesi resmi terverifikasi</strong>
            </div>
        </article>

        <article class="pd-stat-card" style="border-color: rgba(239,68,68,.25);">
            <div class="pd-stat-top">
                <span style="color: #ff8e86;">LOGIN GAGAL / DITOLAK</span>
                <span class="pd-stat-icon" style="background: rgba(239,68,68,.15); color: #ff8e86;">✕</span>
            </div>
            <div class="pd-stat-value" style="color: #ff8e86;">{{ number_format($failedAttempts) }}</div>
            <div class="pd-stat-bottom">
                <span>Potensi kesalahan sandi atau serangan</span>
            </div>
        </article>
    </div>

    <!-- Filter & Search Panel -->
    <div class="pd-panel" style="padding: 18px;">
        <form method="GET" action="{{ route('admin.audit.login') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
            <div style="grid-column: span 2;">
                <label class="pd-label" style="font-size: 10px;">Pencarian Bebas</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Username, IP Address, Browser..." class="pd-input" style="height: 36px;">
            </div>

            <div>
                <label class="pd-label" style="font-size: 10px;">Status Percobaan</label>
                <select name="status" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua Status</option>
                    <option value="Berhasil" {{ request('status') === 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                    <option value="Gagal" {{ request('status') === 'Gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="pd-button pd-button-primary" style="height: 36px; flex: 1;">Filter</button>
                <a href="{{ route('admin.audit.login') }}" class="pd-button pd-button-secondary" style="height: 36px;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table Panel -->
    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div>
                <h2>Log Percobaan Masuk</h2>
                <p>Menampilkan total {{ $histories->total() }} catatan riwayat login</p>
            </div>
        </div>

        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th>Waktu (WITA)</th>
                        <th>Username / Akun</th>
                        <th style="text-align: center;">Status</th>
                        <th>Alamat IP</th>
                        <th>Browser & Perangkat</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $log)
                        <tr>
                            <td style="color: #cbd5e1; font-family: monospace; font-size: 11px;">
                                {{ $log->login_at ? $log->login_at->timezone('Asia/Makassar')->format('d/m/Y H:i:s') : '-' }}
                            </td>
                            <td>
                                <strong style="font-size: 12px; color: #f4f5f7;">{{ $log->username }}</strong>
                                <small style="color: var(--pd-muted); font-size: 9px;">
                                    {{ $log->user ? $log->user->name : 'Akun Tidak Ditemukan' }}
                                </small>
                            </td>
                            <td style="text-align: center;">
                                @if($log->status === 'Berhasil')
                                    <span class="pd-badge is-success"><i></i> Berhasil</span>
                                @else
                                    <span class="pd-badge is-danger"><i></i> Gagal</span>
                                @endif
                            </td>
                            <td style="font-family: monospace; color: var(--pd-accent); font-size: 11px;">
                                {{ $log->ip_address }}
                            </td>
                            <td style="font-size: 11px; color: var(--pd-muted);">
                                {{ $log->browser ?: 'Browser Tidak Dikenal' }} · {{ $log->operating_system ?: 'OS Tidak Dikenal' }}
                            </td>
                            <td style="font-size: 10px; color: var(--pd-dim);">
                                {{ $log->keterangan ?: ($log->status === 'Berhasil' ? 'Autentikasi lolos' : 'Sandi tidak sesuai') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px 16px; color: var(--pd-dim);">
                                Belum ada riwayat login yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($histories->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--pd-border);">
                {{ $histories->links() }}
            </div>
        @endif
    </section>

    <!-- Riwayat pengisian formulir guru -->
    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div>
                <h2>Riwayat Pengisian Form Guru</h2>
                <p>{{ number_format($totalGuruSubmissions, 0, ',', '.') }} data guru terdaftar, termasuk data yang sudah diarsipkan.</p>
            </div>
        </div>

        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th>Waktu Pengisian (WITA)</th>
                        <th>No. Registrasi</th>
                        <th>Nama Guru</th>
                        <th>NIP / NIK</th>
                        <th>Sekolah</th>
                        <th>Pangkat / Golongan</th>
                        <th>Alamat IP Pengirim</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guruSubmissions as $guru)
                        @php($submissionLog = $submissionLogs->get($guru->id))
                        <tr>
                            <td>{{ $guru->created_at?->timezone('Asia/Makassar')->format('d/m/Y H:i:s') ?? '-' }} WITA</td>
                            <td><strong>{{ $guru->nomor_registrasi }}</strong></td>
                            <td>{{ $guru->nama }}</td>
                            <td>
                                <small>NIP: {{ $guru->nip ?: '-' }}</small><br>
                                <small>NIK: {{ $guru->nik ?: '-' }}</small>
                            </td>
                            <td>{{ $guru->sekolah?->nama_sekolah ?? '-' }}</td>
                            <td>{{ $guru->pangkatGolongan?->golongan ?? '-' }}{{ $guru->pangkatGolongan?->nama_pangkat ? ' - ' . $guru->pangkatGolongan->nama_pangkat : '' }}</td>
                            <td>{{ $submissionLog?->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px 16px;">Belum ada data guru yang mengisi formulir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guruSubmissions->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--pd-border);">
                {{ $guruSubmissions->links() }}
            </div>
        @endif
    </section>

</div>
@endsection
