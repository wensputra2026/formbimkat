@extends('layouts.public')
@section('title', 'Layanan')
@section('content')
<section class="pub-page-hero"><div class="pub-container"><div class="pub-eyebrow">LAYANAN</div><h1>Layanan yang mudah <em>diakses.</em></h1><p>Temukan layanan, persyaratan, dan informasi prosedur dalam satu halaman.</p></div></section>
<section class="pub-section"><div class="pub-container"><div class="pub-service-grid pub-service-grid-wide">
    @foreach ([['01','Informasi dan Konsultasi','Informasi umum dan bantuan terkait layanan institusi.'],['02','Pendaftaran Online','Informasi pendaftaran dan proses pengajuan secara daring.'],['03','Unduhan Dokumen','Formulir, pedoman, dan dokumen publik yang dapat diunduh.'],['04','Verifikasi Data','Informasi status dan tahapan pemeriksaan data.'],['05','Pengaduan dan Saran','Saluran komunikasi untuk masukan dan pengaduan.'],['06','Bantuan Pengguna','Panduan penggunaan layanan dan aplikasi.']] as $item)
    <article class="pub-service-card"><div class="pub-service-card-top"><span class="pub-service-icon">↗</span><small>{{ $item[0] }}</small></div><h3>{{ $item[1] }}</h3><p>{{ $item[2] }}</p><a class="pub-card-arrow" href="{{ route('public.contact') }}">Informasi lanjut <b>→</b></a></article>
    @endforeach
</div></div></section>
@endsection
