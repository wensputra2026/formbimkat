@extends('layouts.public')
@section('title', 'Kontak')
@section('content')
<section class="pub-page-hero"><div class="pub-container"><div class="pub-eyebrow">HUBUNGI KAMI</div><h1>Mari <em>terhubung.</em></h1><p>Kami terbuka untuk pertanyaan, informasi, dan masukan dari Anda.</p></div></section>
<section class="pub-section"><div class="pub-container pub-contact-grid">
    <div class="pub-contact-info"><div class="pub-eyebrow">INFORMASI KONTAK</div><h2>Kami senang<br>mendengar dari Anda.</h2><p>Silakan gunakan informasi berikut untuk menghubungi institusi.</p>
        <div class="pub-contact-item"><span>⌖</span><div><small>ALAMAT</small><strong>Jl. Nama Jalan No. 00, Indonesia</strong></div></div>
        <div class="pub-contact-item"><span>✉</span><div><small>EMAIL</small><strong>email@institusi.go.id</strong></div></div>
        <div class="pub-contact-item"><span>☏</span><div><small>TELEPON</small><strong>(000) 0000-0000</strong></div></div>
        <div class="pub-contact-item"><span>◷</span><div><small>JAM PELAYANAN</small><strong>Senin–Jumat, 08.00–16.00</strong></div></div>
    </div>
    <form class="pub-contact-form" method="POST" action="#">
        @csrf
        <h3>Kirim pesan</h3><p>Lengkapi formulir berikut. Hubungkan form ini ke route dan controller sebelum produksi.</p>
        <label>Nama lengkap<input type="text" name="name" placeholder="Nama Anda" required></label>
        <label>Email<input type="email" name="email" placeholder="nama@email.com" required></label>
        <label>Subjek<input type="text" name="subject" placeholder="Perihal pesan" required></label>
        <label>Pesan<textarea name="message" rows="5" placeholder="Tuliskan pesan Anda..." required></textarea></label>
        <button type="submit" class="pub-button pub-button-primary pub-button-large">Kirim pesan <span>→</span></button>
        <small class="pub-form-note">Form demo: belum terhubung ke pengiriman email atau penyimpanan pesan.</small>
    </form>
</div></section>
@endsection
