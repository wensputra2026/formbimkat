@extends('layouts.public')
@section('title', 'Berita')
@section('content')
<section class="pub-page-hero"><div class="pub-container"><div class="pub-eyebrow">PUBLIKASI</div><h1>Berita dan <em>informasi.</em></h1><p>Ikuti kabar, kegiatan, dan pengumuman resmi institusi.</p></div></section>
<section class="pub-section"><div class="pub-container"><div class="pub-news-grid pub-news-grid-page">
    @foreach ([['18 NOV 2026','PENGUMUMAN','Informasi dan pengumuman terbaru institusi'],['12 NOV 2026','KEGIATAN','Kegiatan dan agenda pelayanan institusi'],['05 NOV 2026','INFORMASI','Pembaruan layanan dan informasi publik'],['29 OKT 2026','KEGIATAN','Dokumentasi kegiatan institusi'],['21 OKT 2026','PENGUMUMAN','Pemberitahuan untuk masyarakat'],['14 OKT 2026','INFORMASI','Informasi layanan dan prosedur terbaru']] as $item)
    <article class="pub-news-card"><div class="pub-news-art"><span>{{ $item[1] }}</span><b>INSTITUSI</b><i></i></div><div class="pub-news-meta">{{ $item[0] }} <span>·</span> {{ $item[1] }}</div><h3>{{ $item[2] }}</h3><p>Ini adalah konten contoh. Ganti dengan judul, ringkasan, tanggal, dan gambar berita dari database aplikasi.</p><a href="#">Baca selengkapnya <span>→</span></a></article>
    @endforeach
</div></div></section>
@endsection
