@extends('layouts.admin')

@section('title', 'Dashboard Statistik Data Guru')
@section('page_title', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="pd-page-heading">
        <div>
            <div class="pd-eyebrow">RINGKASAN SISTEM PENDATAAN GURU</div>
            <h1>Dashboard Data Guru<span class="pd-heading-accent">.</span></h1>
            <p>Statistik pendaftaran mandiri, distribusi kategori TPG, dan pemetaan sekolah guru Kementerian Agama.</p>
        </div>
        <div class="pd-heading-actions">
            <a href="{{ route('admin.report.export_excel') }}" class="pd-button pd-button-primary">
                <span>EXPORT EXCEL LENGKAP (MASTER & KATEGORI)</span>
            </a>
            <a href="{{ route('admin.report.export_pdf') }}" target="_blank" class="pd-button pd-button-secondary">
                <span>Cetak Rekap PDF</span>
            </a>
        </div>
    </div>

    <!-- Stat Grid Utama -->
    <div class="pd-stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <!-- Total Guru -->
        <article class="pd-stat-card">
            <div class="pd-stat-top">
                <span>TOTAL GURU TERDAFTAR</span>
                <span class="pd-stat-icon">👥</span>
            </div>
            <div class="pd-stat-value">{{ number_format($totalGuru) }}</div>
            <div class="pd-stat-bottom">
                <strong>Data Aktif</strong>
                <span>bersumber dari formulir mandiri</span>
            </div>
        </article>

        <!-- Guru Penerima TPG -->
        <article class="pd-stat-card">
            <div class="pd-stat-top">
                <span style="color: var(--pd-accent);">GURU PENERIMA TPG</span>
                <span class="pd-stat-icon">🎓</span>
            </div>
            <div class="pd-stat-value" style="color: var(--pd-accent);">{{ number_format($totalTpg) }}</div>
            <div class="pd-stat-bottom">
                <span style="color: var(--pd-muted);">Tercatat dalam 6 Kategori TPG</span>
            </div>
        </article>

        <!-- Master Sekolah -->
        <article class="pd-stat-card">
            <div class="pd-stat-top">
                <span>MASTER SEKOLAH</span>
                <span class="pd-stat-icon">🏫</span>
            </div>
            <div class="pd-stat-value">{{ number_format($totalSekolah) }}</div>
            <div class="pd-stat-bottom">
                <strong style="color: var(--pd-success);">Tersinkronisasi</strong>
                <span>dari database BIMKAT</span>
            </div>
        </article>

        <!-- Master Pangkat/Golongan -->
        <article class="pd-stat-card">
            <div class="pd-stat-top">
                <span>PANGKAT & GOLONGAN</span>
                <span class="pd-stat-icon">🏷️</span>
            </div>
            <div class="pd-stat-value">{{ number_format($totalPangkat) }}</div>
            <div class="pd-stat-bottom">
                <span>Golongan I/a hingga IV/e</span>
            </div>
        </article>
    </div>

    <!-- Grid Tengah: Chart TPG & Top Sekolah -->
    <div class="pd-content-grid">
        <!-- Grafik Distribusi TPG -->
        <section class="pd-panel">
            <div class="pd-panel-heading">
                <div>
                    <h2>Distribusi 6 Kategori TPG</h2>
                    <p>Perbandingan jumlah guru pada tiap kategori resmi TPG</p>
                </div>
                <a href="{{ route('admin.report.index') }}" class="pd-text-link">Detail Laporan →</a>
            </div>
            <div style="position: relative; height: 260px;">
                <canvas id="tpgChart"></canvas>
            </div>
        </section>

        <!-- Top 5 Sekolah -->
        <section class="pd-panel">
            <div class="pd-panel-heading">
                <div>
                    <h2>Top Sekolah Tempat Tugas</h2>
                    <p>Sekolah dengan jumlah guru terdaftar terbanyak</p>
                </div>
                <a href="{{ route('admin.sekolah.index') }}" class="pd-text-link">Semua Sekolah →</a>
            </div>
            <div class="pd-activity-list">
                @forelse($topSchools as $school)
                    <div class="pd-activity-item">
                        <div class="pd-activity-icon">🏫</div>
                        <div style="flex: 1; min-width: 0;">
                            <strong style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $school->nama_sekolah }}
                            </strong>
                            <p style="margin: 3px 0 0; font-size: 11px; color: var(--pd-muted);">
                                NPSN: {{ $school->npsn ?: '-' }}
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span class="pd-badge is-info">
                                <b>{{ $school->total }}</b> Guru
                            </span>
                        </div>
                    </div>
                @empty
                    <p style="color: var(--pd-muted); font-size: 12px; text-align: center; padding: 20px 0;">Belum ada data guru yang terhubung ke sekolah.</p>
                @endforelse
            </div>
        </section>
    </div>

    <!-- Table: Data Guru Terbaru -->
    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div>
                <h2>Data Guru Terbaru Masuk</h2>
                <p>Data pengajuan guru yang baru saja dikirim melalui formulir mandiri publik.</p>
            </div>
            <a href="{{ route('admin.guru.index') }}" class="pd-text-link">Buka Seluruh Data Guru →</a>
        </div>

        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th>NO. REGISTRASI</th>
                        <th>NAMA GURU</th>
                        <th>NIP / NIK</th>
                        <th>SEKOLAH TEMPAT TUGAS</th>
                        <th>KATEGORI TPG</th>
                        <th>WAKTU SUBMIT</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentGurus as $guru)
                        <tr>
                            <td>
                                <strong style="font-family: monospace; color: var(--pd-accent);">{{ $guru->nomor_registrasi }}</strong>
                            </td>
                            <td>
                                <strong>{{ $guru->nama }}</strong>
                                <small>{{ $guru->pangkatGolongan ? $guru->pangkatGolongan->label : '-' }}</small>
                            </td>
                            <td>
                                <span style="font-family: monospace; color: #334155; font-weight: 600;">{{ $guru->nip ?: ($guru->nik ?: '-') }}</span>
                            </td>
                            <td>
                                <span>{{ $guru->sekolah ? $guru->sekolah->nama_sekolah : '-' }}</span>
                                <small>{{ $guru->sekolah ? $guru->sekolah->kabupaten : '' }}</small>
                            </td>
                            <td>
                                <span class="pd-badge is-info"><i></i> {{ $guru->penerima_tpg ?: 'Non-TPG' }}</span>
                            </td>
                            <td>{{ $guru->created_at->diffForHumans() }}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.guru.show', $guru->id) }}" class="pd-button pd-button-secondary" style="font-size: 11px; height: 30px; padding: 0 12px;">
                                    <span>Lihat Detail →</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 36px; color: var(--pd-muted); font-style: italic;">
                                Belum ada data guru yang dikirimkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('tpgChart');
    if (!ctx) return;

    const labels = @json($tpgCategories);
    const data = @json(array_values($tpgCounts));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Guru',
                data: data,
                backgroundColor: [
                    'rgba(234, 88, 12, 0.85)',
                    'rgba(249, 115, 22, 0.85)',
                    'rgba(217, 119, 6, 0.85)',
                    'rgba(245, 158, 11, 0.85)',
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(5, 150, 105, 0.85)',
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#64748b', font: { size: 11 }, precision: 0 }
                }
            }
        }
    });
});
</script>
@endpush
