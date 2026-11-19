@extends('layouts.public')
@section('title', 'Beranda')
@section('meta_description', 'Website resmi INSTITUSI. Temukan profil, layanan, berita, dan informasi terbaru.')

@section('content')
<section class="pub-hero">
    <div class="pub-container pub-hero-grid">
        <div class="pub-hero-copy">
            <div class="pub-eyebrow"><span></span> WEBSITE RESMI INSTITUSI</div>
            <h1>Melayani dengan integritas, <em>bertumbuh bersama.</em></h1>
            <p class="pub-hero-lead">Temukan informasi, layanan, dan publikasi resmi dalam satu ruang digital yang mudah diakses, transparan, dan terpercaya.</p>
            <div class="pub-hero-actions">
                <a href="{{ route('public.about') }}" class="pub-button pub-button-primary pub-button-large">Jelajahi profil <span>→</span></a>
                <a href="{{ route('public.services') }}" class="pub-button pub-button-outline pub-button-large">Lihat layanan</a>
            </div>
            <div class="pub-hero-trust"><span class="pub-trust-icon">✓</span><span>Informasi resmi · Akses mudah · Layanan terbuka</span></div>
        </div>
        <div class="pub-hero-visual" aria-label="Ilustrasi abstrak identitas institusi">
            <div class="pub-visual-orbit orbit-one"></div><div class="pub-visual-orbit orbit-two"></div>
            <div class="pub-visual-core"><span class="pub-core-mark">I</span><small>INSTITUSI</small><strong>INTEGRITAS<br>PELAYANAN<br>KOLABORASI</strong></div>
            <div class="pub-float-card pub-float-top"><span class="pub-float-dot"></span><div><small>AKSES INFORMASI</small><strong>Terbuka untuk semua</strong></div></div>
            <div class="pub-float-card pub-float-bottom"><span class="pub-float-check">✓</span><div><small>LAYANAN DIGITAL</small><strong>Mudah dan efisien</strong></div></div>
            <div class="pub-visual-grid"></div>
        </div>
    </div>
    <div class="pub-hero-bottom-line"></div>
</section>

<section class="pub-stat-strip">
    <div class="pub-container pub-stat-row">
        <div><strong>01</strong><span>Informasi Terpadu</span></div>
        <div><strong>02</strong><span>Layanan Publik</span></div>
        <div><strong>03</strong><span>Publikasi Resmi</span></div>
        <div><strong>04</strong><span>Komunikasi Terbuka</span></div>
    </div>
</section>

<section class="pub-section">
    <div class="pub-container">
        <div class="pub-section-heading">
            <div><div class="pub-eyebrow">APA YANG KAMI SEDIAKAN</div><h2>Informasi dan layanan<br><em>dalam satu tempat.</em></h2></div>
            <a href="{{ route('public.services') }}" class="pub-text-link">Semua layanan <span>→</span></a>
        </div>
        <div class="pub-service-grid">
            @foreach ([
                ['n'=>'01','icon'=>'▤','title'=>'Informasi Institusi','desc'=>'Kenali profil, visi, misi, struktur, dan informasi resmi institusi.'],
                ['n'=>'02','icon'=>'◫','title'=>'Layanan Publik','desc'=>'Akses informasi layanan dan panduan prosedur dengan lebih mudah.'],
                ['n'=>'03','icon'=>'▧','title'=>'Berita & Pengumuman','desc'=>'Ikuti kabar, agenda, dan pengumuman terbaru dari institusi.'],
            ] as $service)
                <a href="{{ route('public.services') }}" class="pub-service-card">
                    <div class="pub-service-card-top"><span class="pub-service-icon">{{ $service['icon'] }}</span><small>{{ $service['n'] }}</small></div>
                    <h3>{{ $service['title'] }}</h3><p>{{ $service['desc'] }}</p><span class="pub-card-arrow">Jelajahi <b>↗</b></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="pub-feature-band">
    <div class="pub-container pub-feature-grid">
        <div><div class="pub-eyebrow">KOMITMEN KAMI</div><h2>Ruang digital yang dibangun untuk <em>melayani.</em></h2></div>
        <div><p>Kami berkomitmen menyediakan informasi yang jelas, layanan yang mudah dijangkau, serta komunikasi yang terbuka. Website ini menjadi pintu masuk untuk mengenal institusi dan mendapatkan informasi yang dibutuhkan.</p><a href="{{ route('public.about') }}" class="pub-button pub-button-light">Kenali institusi <span>→</span></a></div>
    </div>
</section>

<section class="pub-section pub-news-section">
    <div class="pub-container">
        <div class="pub-section-heading">
            <div><div class="pub-eyebrow">KABAR TERBARU</div><h2>Berita dan <em>pengumuman.</em></h2></div>
            <a href="{{ route('public.news') }}" class="pub-text-link">Lihat semua berita <span>→</span></a>
        </div>
        <div class="pub-news-grid">
            @foreach ([
                ['date'=>'18 NOV 2026','tag'=>'PENGUMUMAN','title'=>'Informasi dan pengumuman terbaru institusi','desc'=>'Konten berita ini merupakan contoh. Hubungkan dengan modul berita aplikasi Anda.'],
                ['date'=>'12 NOV 2026','tag'=>'KEGIATAN','title'=>'Kegiatan dan agenda pelayanan institusi','desc'=>'Sajikan informasi kegiatan secara ringkas agar mudah dibaca pengunjung.'],
                ['date'=>'05 NOV 2026','tag'=>'INFORMASI','title'=>'Pembaruan layanan dan informasi publik','desc'=>'Gunakan bagian ini untuk menampilkan publikasi resmi dan informasi penting.'],
            ] as $news)
                <article class="pub-news-card"><div class="pub-news-art"><span>{{ $news['tag'] }}</span><b>INSTITUSI</b><i></i></div><div class="pub-news-meta">{{ $news['date'] }} <span>·</span> {{ $news['tag'] }}</div><h3>{{ $news['title'] }}</h3><p>{{ $news['desc'] }}</p><a href="{{ route('public.news') }}">Baca selengkapnya <span>→</span></a></article>
            @endforeach
        </div>
    </div>
</section>

<section class="pub-cta">
    <div class="pub-container pub-cta-inner"><div><div class="pub-eyebrow">ADA YANG INGIN DITANYAKAN?</div><h2>Kami siap membantu Anda.</h2><p>Hubungi kami untuk mendapatkan informasi lebih lanjut.</p></div><a href="{{ route('public.contact') }}" class="pub-button pub-button-primary pub-button-large">Hubungi kami <span>→</span></a></div>
</section>
@endsection
