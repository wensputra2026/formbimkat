@extends('layouts.public')
@section('title', 'Beranda')
@section('meta_description', 'Portal Resmi Pendataan Guru Agama Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan.')

@section('content')
<section class="pub-hero">
    <div class="pub-container pub-hero-grid">
        <div class="pub-hero-copy">
            <div class="pub-eyebrow"><span></span> KEMENTERIAN AGAMA KAB. TIMOR TENGAH SELATAN</div>
            <h1>Pendataan Guru Agama Katolik <em>Terpadu & Akurat.</em></h1>
            <p class="pub-hero-lead">Portal resmi Seksi Bimas Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan untuk pengumpulan, verifikasi, dan pemutakhiran data kepegawaian guru secara mandiri, transparan, dan terpercaya.</p>
            <div class="pub-hero-actions">
                <a href="{{ route('guru.create') }}" class="pub-button pub-button-primary pub-button-large">Isi Formulir Pendataan <span>→</span></a>
                <a href="{{ route('guru.cek_status') }}" class="pub-button pub-button-outline pub-button-large">Cek Status Pengajuan</a>
            </div>
            <div class="pub-hero-trust"><span class="pub-trust-icon">✓</span><span>Portal Resmi · Bebas Login Guru · Nomor Registrasi Unik</span></div>
        </div>
        <div class="pub-hero-visual" aria-label="Ilustrasi identitas Kementerian Agama">
            <div class="pub-visual-orbit orbit-one"></div><div class="pub-visual-orbit orbit-two"></div>
            <div class="pub-visual-core">
                <span class="pub-core-mark">
                    <svg style="width: 30px; height: 30px; color: #28170b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </span>
                <small>BIMAS KATOLIK</small>
                <strong>INTEGRITAS<br>AKURASI<br>PELAYANAN</strong>
            </div>
            <div class="pub-float-card pub-float-top"><span class="pub-float-dot"></span><div><small>PENDATAAN MANDIRI</small><strong>Langsung Tanpa Akun</strong></div></div>
            <div class="pub-float-card pub-float-bottom"><span class="pub-float-check">✓</span><div><small>VERIFIKASI RESMI</small><strong>Nomor Registrasi Unik</strong></div></div>
            <div class="pub-visual-grid"></div>
        </div>
    </div>
    <div class="pub-hero-bottom-line"></div>
</section>

<section class="pub-stat-strip">
    <div class="pub-container pub-stat-row">
        <div><strong>01</strong><span>Pendataan Mandiri</span></div>
        <div><strong>02</strong><span>Verifikasi Berkas</span></div>
        <div><strong>03</strong><span>Transparansi TPG</span></div>
        <div><strong>04</strong><span>Master Sekolah Valid</span></div>
    </div>
</section>

<section class="pub-section">
    <div class="pub-container">
        <div class="pub-section-heading">
            <div><div class="pub-eyebrow">LAYANAN UTAMA</div><h2>Layanan Terpadu<br><em>Kepegawaian Guru.</em></h2></div>
            <a href="{{ route('public.services') }}" class="pub-text-link">Semua layanan <span>→</span></a>
        </div>
        <div class="pub-service-grid">
            <a href="{{ route('guru.create') }}" class="pub-service-card">
                <div class="pub-service-card-top"><span class="pub-service-icon">📝</span><small>01</small></div>
                <h3>Formulir Pendataan Guru</h3>
                <p>Isi data kepegawaian, NIP, NIK, pangkat golongan, gaji pokok, KGB, pensiun, dan pilih sekolah tempat tugas.</p>
                <span class="pub-card-arrow">Buka Formulir <b>↗</b></span>
            </a>
            <a href="{{ route('guru.cek_status') }}" class="pub-service-card">
                <div class="pub-service-card-top"><span class="pub-service-icon">🔍</span><small>02</small></div>
                <h3>Cek Status Pengajuan</h3>
                <p>Periksa hasil verifikasi Admin (Menunggu, Disetujui, atau Ditolak) menggunakan nomor registrasi Anda.</p>
                <span class="pub-card-arrow">Cek Status <b>↗</b></span>
            </a>
            <a href="{{ route('public.services') }}" class="pub-service-card">
                <div class="pub-service-card-top"><span class="pub-service-icon">📋</span><small>03</small></div>
                <h3>Kategori TPG & Kepegawaian</h3>
                <p>Informasi 6 kategori resmi TPG: Kemenag, Kemenag 2026, Dinas, Dinas 2026, PPPK, dan PPPK 2026.</p>
                <span class="pub-card-arrow">Lihat Info <b>↗</b></span>
            </a>
        </div>
    </div>
</section>

<section class="pub-feature-band">
    <div class="pub-container pub-feature-grid">
        <div><div class="pub-eyebrow">KOMITMEN KAMI</div><h2>Layanan kepegawaian yang modern, <em>mudah & akuntabel.</em></h2></div>
        <div>
            <p>Seksi Bimas Katolik berkomitmen memberikan pelayanan terbaik bagi seluruh Guru Agama Katolik di Kabupaten Timor Tengah Selatan. Sistem ini menjamin validitas NIP/NIK, mencegah data ganda, dan mempermudah rekapitulasi data.</p>
            <a href="{{ route('public.about') }}" class="pub-button pub-button-light">Kenali Seksi Bimas Katolik <span>→</span></a>
        </div>
    </div>
</section>

<section class="pub-section pub-news-section">
    <div class="pub-container">
        <div class="pub-section-heading">
            <div><div class="pub-eyebrow">PENGUMUMAN TERBARU</div><h2>Berita dan <em>informasi resmi.</em></h2></div>
            <a href="{{ route('public.news') }}" class="pub-text-link">Lihat semua pengumuman <span>→</span></a>
        </div>
        <div class="pub-news-grid">
            <article class="pub-news-card">
                <div class="pub-news-art"><span>PENGUMUMAN</span><b>BIMAS KATOLIK</b><i></i></div>
                <div class="pub-news-meta">18 NOV 2026 <span>·</span> PENGUMUMAN</div>
                <h3>Pembukaan Periode Pendataan Guru Agama Katolik Tahun 2026</h3>
                <p>Seluruh guru agama Katolik di Kabupaten TTS diimbau untuk mengisi formulir pendataan mandiri secara daring melalui portal resmi ini.</p>
                <a href="{{ route('guru.create') }}">Mulai Pengisian <span>→</span></a>
            </article>
            <article class="pub-news-card">
                <div class="pub-news-art"><span>VERIFIKASI</span><b>BIMAS KATOLIK</b><i></i></div>
                <div class="pub-news-meta">12 NOV 2026 <span>·</span> KEPEGAWAIAN</div>
                <h3>Pemutakhiran Data Gaji Pokok, KGB dan Batas Usia Pensiun</h3>
                <p>Panduan pengisian nominal gaji pokok terakhir dan perkiraan bulan/tahun kenaikan gaji berkala (KGB) berikutnya.</p>
                <a href="{{ route('public.news') }}">Baca panduan <span>→</span></a>
            </article>
            <article class="pub-news-card">
                <div class="pub-news-art"><span>TPG</span><b>BIMAS KATOLIK</b><i></i></div>
                <div class="pub-news-meta">05 NOV 2026 <span>·</span> INFORMASI</div>
                <h3>Sinkronisasi 6 Kategori Penerima Tunjangan Profesi Guru</h3>
                <p>Informasi validasi penerima TPG Kemenag, Dinas, dan PPPK agar rekapitulasi anggaran berjalan akurat.</p>
                <a href="{{ route('public.news') }}">Baca selengkapnya <span>→</span></a>
            </article>
        </div>
    </div>
</section>

<section class="pub-cta">
    <div class="pub-container pub-cta-inner">
        <div>
            <div class="pub-eyebrow">BUTUH BANTUAN ATAU INFORMASI?</div>
            <h2>Hubungi Seksi Bimas Katolik Kemenag Kab. TTS.</h2>
            <p>Tim admin siap membantu pertanyaan seputar pengisian formulir dan verifikasi nomor registrasi.</p>
        </div>
        <a href="{{ route('public.contact') }}" class="pub-button pub-button-primary pub-button-large">Hubungi Kami <span>→</span></a>
    </div>
</section>
@endsection
