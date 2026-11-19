@extends('layouts.admin')

@section('title', 'Master Sekolah — BIMKAT')
@section('page_title', 'Master Sekolah')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Heading -->
    <div class="pd-page-heading" style="margin-bottom: 8px;">
        <div>
            <div class="pd-eyebrow">SUMBER MASTER DATA RESMI BIMKAT</div>
            <h1>Master Data Sekolah<span class="pd-heading-accent">.</span></h1>
            <p>Daftar satuan pendidikan tempat tugas guru. Data disinkronkan secara aman dari proyek BIMKAT.</p>
        </div>
        <div class="pd-heading-actions">
            <!-- Sync Button -->
            <form action="{{ route('admin.sekolah.sync') }}" method="POST" data-swal-title="Sinkronkan master sekolah?" data-swal-confirm="Data sekolah akan diperbarui dari BIMKAT." data-swal-icon="question" data-swal-confirm-button="Ya, sinkronkan" style="margin: 0;">
                @csrf
                <button type="submit" class="pd-button pd-button-primary">
                    <span>↻ SINKRONKAN DARI BIMKAT</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="pd-panel" style="padding: 18px;">
        <form method="GET" action="{{ route('admin.sekolah.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
            <!-- Search -->
            <div style="grid-column: span 2;">
                <label class="pd-label" style="font-size: 10px;">Pencarian Sekolah</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama sekolah, NPSN, atau alamat..." class="pd-input" style="height: 36px;">
            </div>

            <!-- Filter Jenjang -->
            <div>
                <label class="pd-label" style="font-size: 10px;">Jenjang</label>
                <select name="jenjang" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua Jenjang</option>
                    @foreach($jenjangList as $jj)
                        <option value="{{ $jj }}" {{ request('jenjang') == $jj ? 'selected' : '' }}>{{ $jj }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label class="pd-label" style="font-size: 10px;">Status Sekolah</label>
                <select name="status_sekolah" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua Status</option>
                    <option value="Negeri" {{ request('status_sekolah') == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                    <option value="Swasta" {{ request('status_sekolah') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                </select>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="pd-button pd-button-primary" style="height: 36px; flex: 1;">
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.sekolah.index') }}" class="pd-button pd-button-secondary" style="height: 36px;">
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Panel -->
    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div>
                <h2>Daftar Satuan Pendidikan</h2>
                <p>Total data tersimpan: <strong>{{ number_format($totalSekolah) }}</strong> Sekolah</p>
            </div>
        </div>

        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">NO.</th>
                        <th>NAMA SEKOLAH</th>
                        <th>NPSN</th>
                        <th>JENJANG & STATUS</th>
                        <th>ALAMAT SEKOLAH</th>
                        <th>KABUPATEN / PROVINSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sekolahs as $index => $s)
                        <tr>
                            <td style="text-align: center; color: #64748b; font-weight: 700;">
                                {{ $sekolahs->firstItem() + $index }}
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size: 13.5px; font-weight: 700;">{{ $s->nama_sekolah }}</strong>
                                <small style="color: #64748b; font-size: 10px;">ID: {{ $s->id_sekolah }}</small>
                            </td>
                            <td>
                                <strong style="font-family: monospace; color: #ea580c; font-size: 13px; font-weight: 700;">{{ $s->npsn ?: '-' }}</strong>
                            </td>
                            <td>
                                <span class="pd-badge {{ $s->status_sekolah === 'Negeri' ? 'is-success' : 'is-warning' }}">
                                    <i></i> {{ $s->jenjang }} — {{ $s->status_sekolah }}
                                </span>
                            </td>
                            <td style="max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #334155;">
                                {{ $s->alamat_sekolah ?: '-' }}
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size: 13px;">{{ $s->kabupaten ?: 'Timor Tengah Selatan' }}</strong>
                                <small style="color: #64748b; font-size: 11px;">{{ $s->provinsi ?: 'Nusa Tenggara Timur' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px; color: #64748b; font-style: italic;">
                                Tidak ada data sekolah ditemukan. Silakan klik tombol "SINKRONKAN DARI BIMKAT".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sekolahs->hasPages())
            <div style="padding: 16px; border-top: 1px solid var(--pd-border);">
                {{ $sekolahs->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
