@extends('layouts.public')
@section('title', 'Layanan Kepegawaian')
@section('meta_description', 'Layanan Kepegawaian dan Pendataan Guru Agama Katolik Kemenag Kab. Timor Tengah Selatan.')

@section('content')
<section class="pub-page-hero">
    <div class="pub-container">
        <div class="pub-eyebrow">LAYANAN PUBLIK</div>
        <h1>Layanan Kepegawaian <em>Guru Agama Katolik.</em></h1>
        <p>Akses informasi pendaftaran, verifikasi, dan administrasi kepegawaian secara transparan.</p>
    </div>
</section>

<section class="pub-section">
    <div class="pub-container">
        <div class="pub-service-grid pub-service-grid-wide">
            @foreach ([
                ['01','Pendataan Guru Mandiri','Pengisian formulir identitas, NIP/NIK, pangkat, gaji pokok, KGB, pensiun, dan sekolah tempat tugas.', route('guru.create')],
                ['02','Cek Status & Verifikasi','Pemeriksaan status pengajuan data oleh tim verifikator Admin dengan nomor registrasi.', route('guru.cek_status')],
                ['03','Validasi Tunjangan Profesi (TPG)','Pengelompokan 6 kategori resmi TPG: Kemenag, Kemenag 2026, Dinas, Dinas 2026, PPPK, dan PPPK 2026.', route('public.contact')],
                ['04','Sinkronisasi Master Sekolah','Pemadanan data sekolah tempat tugas dengan database resmi 577 sekolah se-Kabupaten TTS.', route('guru.create')],
                ['05','Kenaikan Gaji Berkala (KGB)','Pencatatan estimasi bulan dan tahun KGB berikutnya untuk usulan kenaikan gaji tepat waktu.', route('public.contact')],
                ['06','Konsultasi Administrasi','Saluran komunikasi langsung bagi guru yang membutuhkan panduan pengisian atau perbaikan berkas.', route('public.contact')]
            ] as $item)
            <article class="pub-service-card">
                <div class="pub-service-card-top"><span class="pub-service-icon">↗</span><small>{{ $item[0] }}</small></div>
                <h3>{{ $item[1] }}</h3>
                <p>{{ $item[2] }}</p>
                <a class="pub-card-arrow" href="{{ $item[3] }}">Buka Layanan <b>→</b></a>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
