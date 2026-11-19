@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="pd-page-heading">
        <div>
            <div class="pd-eyebrow">RINGKASAN SISTEM</div>
            <h1>Selamat datang kembali<span class="pd-heading-accent">.</span></h1>
            <p>Berikut ringkasan aktivitas dan informasi aplikasi Anda hari ini.</p>
        </div>
        <div class="pd-heading-actions">
            <button class="pd-button pd-button-secondary">↗ <span>Unduh laporan</span></button>
            <button class="pd-button pd-button-primary">＋ <span>Tambah data</span></button>
        </div>
    </div>

    <div class="pd-stat-grid">
        @foreach ([
            ['label' => 'Total Data', 'value' => '12.840', 'change' => '+12,8%', 'icon' => '▤', 'note' => 'dibanding bulan lalu'],
            ['label' => 'Pengguna Aktif', 'value' => '1.284', 'change' => '+8,2%', 'icon' => '♙', 'note' => 'dibanding bulan lalu'],
            ['label' => 'Menunggu Verifikasi', 'value' => '48', 'change' => 'Perlu ditinjau', 'icon' => '◷', 'note' => 'data membutuhkan tindakan'],
            ['label' => 'Laporan Selesai', 'value' => '96,4%', 'change' => '+4,6%', 'icon' => '✓', 'note' => 'tingkat penyelesaian'],
        ] as $stat)
            <article class="pd-stat-card">
                <div class="pd-stat-top">
                    <span>{{ $stat['label'] }}</span>
                    <span class="pd-stat-icon">{{ $stat['icon'] }}</span>
                </div>
                <div class="pd-stat-value">{{ $stat['value'] }}</div>
                <div class="pd-stat-bottom"><strong>{{ $stat['change'] }}</strong><span>{{ $stat['note'] }}</span></div>
            </article>
        @endforeach
    </div>

    <div class="pd-content-grid">
        <section class="pd-panel pd-panel-main">
            <div class="pd-panel-heading">
                <div><h2>Aktivitas</h2><p>Ringkasan aktivitas aplikasi</p></div>
                <button class="pd-select">7 hari terakhir⌄</button>
            </div>
            <div class="pd-chart-placeholder">
                <div class="pd-chart-y"><span>100</span><span>75</span><span>50</span><span>25</span><span>0</span></div>
                <div class="pd-chart-area">
                    <div class="pd-chart-grid"><i></i><i></i><i></i><i></i><i></i></div>
                    <svg viewBox="0 0 700 220" preserveAspectRatio="none" role="img" aria-label="Grafik aktivitas contoh">
                        <defs><linearGradient id="pd-chart-fill" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#f59a45" stop-opacity=".28"/><stop offset="100%" stop-color="#f59a45" stop-opacity="0"/></linearGradient></defs>
                        <path d="M0 170 C50 160 55 120 105 135 S170 155 205 105 S270 125 310 95 S370 120 410 75 S470 110 510 65 S570 90 610 45 S660 70 700 25 L700 220 L0 220Z" fill="url(#pd-chart-fill)"/>
                        <path d="M0 170 C50 160 55 120 105 135 S170 155 205 105 S270 125 310 95 S370 120 410 75 S470 110 510 65 S570 90 610 45 S660 70 700 25" fill="none" stroke="#f59a45" stroke-width="3" vector-effect="non-scaling-stroke"/>
                    </svg>
                    <div class="pd-chart-x"><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span></div>
                </div>
            </div>
        </section>

        <section class="pd-panel pd-activity-panel">
            <div class="pd-panel-heading"><div><h2>Aktivitas terbaru</h2><p>Pembaruan terakhir</p></div><a href="#">Lihat semua</a></div>
            <div class="pd-activity-list">
                @foreach ([
                    ['title' => 'Data baru ditambahkan', 'desc' => 'Data berhasil disimpan ke sistem', 'time' => '5 menit lalu', 'icon' => '＋'],
                    ['title' => 'Verifikasi selesai', 'desc' => 'Pemeriksaan data telah selesai', 'time' => '28 menit lalu', 'icon' => '✓'],
                    ['title' => 'Pengguna masuk', 'desc' => 'Sesi pengguna baru terdeteksi', 'time' => '1 jam lalu', 'icon' => '♙'],
                    ['title' => 'Laporan dibuat', 'desc' => 'Laporan periode terbaru tersedia', 'time' => '2 jam lalu', 'icon' => '▤'],
                ] as $activity)
                    <div class="pd-activity-item">
                        <span class="pd-activity-icon">{{ $activity['icon'] }}</span>
                        <div><strong>{{ $activity['title'] }}</strong><p>{{ $activity['desc'] }}</p><small>{{ $activity['time'] }}</small></div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div><h2>Data terbaru</h2><p>Contoh tabel untuk data utama aplikasi</p></div>
            <a href="#" class="pd-text-link">Lihat seluruh data →</a>
        </div>
        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead><tr><th>Nama / Identitas</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @foreach ([
                        ['name' => 'Contoh Data Satu', 'code' => 'REG-2026-001', 'category' => 'Administrasi', 'date' => '18 Nov 2026', 'status' => 'Terverifikasi'],
                        ['name' => 'Contoh Data Dua', 'code' => 'REG-2026-002', 'category' => 'Operasional', 'date' => '17 Nov 2026', 'status' => 'Menunggu'],
                        ['name' => 'Contoh Data Tiga', 'code' => 'REG-2026-003', 'category' => 'Administrasi', 'date' => '16 Nov 2026', 'status' => 'Terverifikasi'],
                    ] as $row)
                        <tr>
                            <td><strong>{{ $row['name'] }}</strong><small>{{ $row['code'] }}</small></td>
                            <td>{{ $row['category'] }}</td><td>{{ $row['date'] }}</td>
                            <td><span class="pd-badge {{ $row['status'] === 'Terverifikasi' ? 'is-success' : 'is-warning' }}"><i></i>{{ $row['status'] }}</span></td>
                            <td><button class="pd-row-more" aria-label="Aksi lainnya">···</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
