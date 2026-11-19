@extends('layouts.admin')

@section('title', 'Laporan & Export Data')
@section('page_title', 'Laporan & Export')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Heading -->
    <div class="pd-page-heading" style="margin-bottom: 8px;">
        <div>
            <div class="pd-eyebrow">PUSAT REKAPITULASI & DOKUMEN</div>
            <h1>Laporan &amp; Ekspor Data Guru<span class="pd-heading-accent">.</span></h1>
            <p>Ekspor laporan rekapitulasi data guru ke format Microsoft Excel (.xlsx Master Semua & Kategori Sheet) dan dokumen cetak PDF resmi.</p>
        </div>
        <div class="pd-heading-actions">
            <!-- EXPORT EXCEL MASTER & KATEGORI -->
            <a href="{{ route('admin.report.export_excel', ['status' => $statusFilter]) }}" class="pd-button pd-button-primary" style="height: 40px; padding: 0 18px; font-weight: 800;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>EXPORT EXCEL LENGKAP (MASTER & KATEGORI)</span>
            </a>

            <!-- PRINT / CETAK PDF -->
            <a href="{{ route('admin.report.export_pdf', array_merge(request()->all(), ['mode' => 'stream'])) }}" target="_blank" class="pd-button pd-button-secondary" style="height: 40px; padding: 0 16px;">
                <svg style="width: 16px; height: 16px; color: #ff8e86;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span>Cetak Rekap PDF</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Laporan -->
    <div class="pd-stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
        <article class="pd-stat-card">
            <div class="pd-stat-top">
                <span>TOTAL GURU SESUAI FILTER</span>
                <span class="pd-stat-icon">👥</span>
            </div>
            <div class="pd-stat-value">{{ number_format($totalRecords) }} <span style="font-size: 14px; font-weight: 600; color: var(--pd-muted);">Orang</span></div>
            <div class="pd-stat-bottom">
                <strong>Status: {{ $statusFilter }}</strong>
                <span>tercakup dalam rekap</span>
            </div>
        </article>

        <article class="pd-stat-card" style="border-color: rgba(69,198,154,.25);">
            <div class="pd-stat-top">
                <span style="color: var(--pd-success);">TOTAL ANGGARAN GAJI POKOK</span>
                <span class="pd-stat-icon" style="background: rgba(69,198,154,.15); color: var(--pd-success);">💰</span>
            </div>
            <div class="pd-stat-value" style="color: var(--pd-success); font-size: 22px; font-family: monospace;">
                Rp {{ number_format($totalGaji, 2, ',', '.') }}
            </div>
            <div class="pd-stat-bottom">
                <span>Akumulasi nominal per bulan</span>
            </div>
        </article>

        <article class="pd-stat-card" style="border-color: rgba(245,154,69,.25);">
            <div class="pd-stat-top">
                <span style="color: var(--pd-accent);">FORMAT EXCEL 6 SHEET</span>
                <span class="pd-stat-icon">📊</span>
            </div>
            <div class="pd-stat-value" style="font-size: 18px; color: var(--pd-accent);">6 Lembar Kerja</div>
            <div class="pd-stat-bottom">
                <span>Kemenag, Kemenag 2026, Dinas, Dinas 2026, PPPK, PPPK 2026</span>
            </div>
        </article>
    </div>

    <!-- Filter & Pencarian Panel -->
    <div class="pd-panel" style="padding: 18px;">
        <form method="GET" action="{{ route('admin.report.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
            <div>
                <label class="pd-label" style="font-size: 10px;">Status Verifikasi</label>
                <select name="status" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="Disetujui" {{ $statusFilter === 'Disetujui' ? 'selected' : '' }}>Hanya Disetujui (Default Ekspor)</option>
                    <option value="Menunggu Verifikasi" {{ $statusFilter === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Ditolak" {{ $statusFilter === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Semua" {{ $statusFilter === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                </select>
            </div>

            <div>
                <label class="pd-label" style="font-size: 10px;">Kategori TPG</label>
                <select name="tpg" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua Kategori TPG</option>
                    @foreach($tpgChoices as $tpg)
                        <option value="{{ $tpg }}" {{ request('tpg') === $tpg ? 'selected' : '' }}>{{ $tpg }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="pd-label" style="font-size: 10px;">Pangkat / Golongan</label>
                <select name="pangkat_id" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua Pangkat</option>
                    @foreach($pangkats as $p)
                        <option value="{{ $p->id }}" {{ request('pangkat_id') == $p->id ? 'selected' : '' }}>{{ $p->golongan }} - {{ $p->pangkat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="pd-label" style="font-size: 10px;">Kabupaten / Kota</label>
                <select name="kabupaten" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua Wilayah</option>
                    @foreach($kabupatens as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="pd-button pd-button-primary" style="height: 36px; flex: 1;">Filter</button>
                <a href="{{ route('admin.report.index') }}" class="pd-button pd-button-secondary" style="height: 36px;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table Panel Preview -->
    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div>
                <h2>Pratinjau Data Laporan</h2>
                <p>Menampilkan {{ $gurus->count() }} data dari total {{ $gurus->total() }} hasil saringan</p>
            </div>
            <div style="font-size: 11px; color: var(--pd-muted);">
                Status: <strong style="color: var(--pd-accent);">{{ $statusFilter }}</strong>
            </div>
        </div>

        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th>No. Reg & Identitas</th>
                        <th>Satuan Pendidikan</th>
                        <th>Pangkat / Jabatan</th>
                        <th style="text-align: right;">Gaji Pokok</th>
                        <th style="text-align: center;">Berkala (KGB)</th>
                        <th style="text-align: center;">Pensiun</th>
                        <th style="text-align: center;">Kategori TPG</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $guru)
                        <tr>
                            <td>
                                <strong style="font-size: 12px; color: #0f172a;">{{ $guru->nama }}</strong>
                                <small style="font-family: monospace; color: var(--pd-accent); font-size: 10px;">{{ $guru->nomor_registrasi }}</small>
                                <small style="color: #475569; font-size: 9px;">
                                    @if($guru->nip) NIP: {{ $guru->nip }} @endif
                                    @if($guru->nip && $guru->nik) · @endif
                                    @if($guru->nik) NIK: {{ $guru->nik }} @endif
                                </small>
                            </td>
                            <td>
                                <strong style="font-size: 11px; color: #0f172a;">{{ $guru->sekolah->nama_sekolah ?? '-' }}</strong>
                                <small style="color: #475569; font-size: 9px;">
                                    NPSN: {{ $guru->sekolah->npsn ?? '-' }} · {{ $guru->sekolah->kabupaten ?? '-' }}
                                </small>
                            </td>
                            <td>
                                <strong style="font-size: 11px;">{{ $guru->jabatan->nama_jabatan ?? '-' }}</strong>
                                <small style="color: #475569; font-size: 9px;">{{ $guru->pangkatGolongan->golongan ?? '-' }} ({{ $guru->pangkatGolongan->pangkat ?? '-' }})</small>
                            </td>
                            <td style="text-align: right; font-family: monospace; font-weight: 700; color: var(--pd-success);">
                                Rp {{ number_format($guru->gaji_pokok, 2, ',', '.') }}
                            </td>
                            <td style="text-align: center; font-size: 10px; color: #334155; font-weight: 600;">
                                {{ $guru->kgb_formatted }}
                            </td>
                            <td style="text-align: center; font-size: 10px; color: #334155; font-weight: 600;">
                                {{ $guru->pensiun_formatted }}
                            </td>
                            <td style="text-align: center;">
                                <span class="pd-badge is-warning">{{ $guru->penerima_tpg ?? 'Non-TPG' }}</span>
                            </td>
                            <td style="text-align: center;">
                                @if($guru->status_verifikasi === 'Disetujui')
                                    <span class="pd-badge is-success"><i></i> Disetujui</span>
                                @elseif($guru->status_verifikasi === 'Ditolak')
                                    <span class="pd-badge is-danger"><i></i> Ditolak</span>
                                @else
                                    <span class="pd-badge is-warning"><i></i> Menunggu</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 48px 16px; color: var(--pd-dim);">
                                Tidak ada data guru yang memenuhi filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gurus->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--pd-border);">
                {{ $gurus->links() }}
            </div>
        @endif
    </section>

</div>
@endsection
