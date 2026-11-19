@extends('layouts.public')
@section('title', 'Berita & Pengumuman')
@section('meta_description', 'Publikasi, berita, dan pengumuman resmi Seksi Bimas Katolik Kemenag Kab. Timor Tengah Selatan.')

@section('content')
<section class="pub-page-hero">
    <div class="pub-container">
        <div class="pub-eyebrow">PUBLIKASI RESMI</div>
        <h1>Pengumuman & <em>Berita Terkini.</em></h1>
        <p>Ikuti pemutakhiran jadwal, kebijakan kepegawaian, dan pengumuman resmi Seksi Bimas Katolik.</p>
    </div>
</section>

<section class="pub-section">
    <div class="pub-container">
        <div class="pub-news-grid pub-news-grid-page">
            @foreach ([
                ['18 NOV 2026','PENGUMUMAN','Pembukaan Portal Pendataan Guru Agama Katolik Mandiri Tahun 2026','Seluruh Guru Agama Katolik PNS, PPPK, dan Non-PNS di lingkungan Kemenag Kab. Timor Tengah Selatan wajib mengisi formulir pendataan mandiri.', route('guru.create')],
                ['12 NOV 2026','KEPEGAWAIAN','Verifikasi Kenaikan Gaji Berkala (KGB) dan Batas Usia Pensiun Guru','Panduan perhitungan masa berkala 2 tahunan dan batas usia pensiun berdasarkan SK pengangkatan terakhir.', route('public.services')],
                ['05 NOV 2026','TPG','Ketentuan Penerima Tunjangan Profesi Guru (TPG) Tahun Anggaran 2026','Penegasan enam kategori penerima TPG untuk kelancaran administrasi penyaluran tunjangan profesi.', route('public.services')],
                ['29 OKT 2026','MASTER DATA','Sinkronisasi Master Referensi Sekolah se-Kabupaten Timor Tengah Selatan','Pemadanan 577 data sekolah negeri dan swasta untuk memastikan data tempat tugas guru valid.', route('public.about')],
                ['21 OKT 2026','PELAYANAN','Standar Operasional Prosedur (SOP) Perbaikan Data dan Verifikasi Berkas','Prosedur koreksi data yang dilakukan oleh Admin Bimas Katolik atas pengajuan yang memerlukan perbaikan.', route('public.contact')],
                ['14 OKT 2026','INFORMASI','Pengamanan Data Pribadi dan Nomor Registrasi Unik Guru','Pentingnya menyimpan nomor registrasi unik bukti pengajuan dan kewaspadaan terhadap pihak tidak bertanggung jawab.', route('guru.cek_status')]
            ] as $item)
            <article class="pub-news-card">
                <div class="pub-news-art"><span>{{ $item[1] }}</span><b>BIMAS KATOLIK</b><i></i></div>
                <div class="pub-news-meta">{{ $item[0] }} <span>·</span> {{ $item[1] }}</div>
                <h3>{{ $item[2] }}</h3>
                <p>{{ $item[3] }}</p>
                <a href="{{ $item[4] }}">Informasi lengkap <span>→</span></a>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
