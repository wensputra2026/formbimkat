<footer class="pub-footer">
    <div class="pub-container">
        <div class="pub-footer-grid">
            <div class="pub-footer-about">
                <a href="{{ route('public.home') }}" class="pub-brand pub-brand-footer">
                    <span class="pub-brand-mark">I</span>
                    <span class="pub-brand-text"><strong>INSTITUSI</strong><small>INFORMASI RESMI</small></span>
                </a>
                <p>Melayani dengan integritas, membangun kepercayaan, dan menghadirkan informasi yang terbuka bagi masyarakat.</p>
            </div>
            <div><h3>Navigasi</h3><a href="{{ route('public.about') }}">Profil</a><a href="{{ route('public.services') }}">Layanan</a><a href="{{ route('public.news') }}">Berita</a></div>
            <div><h3>Informasi</h3><a href="{{ route('public.contact') }}">Kontak</a><a href="#">Kebijakan Privasi</a><a href="#">Pusat Bantuan</a></div>
            <div><h3>Hubungi Kami</h3><p>Jl. Nama Jalan No. 00</p><p>email@institusi.go.id</p><p>(000) 0000-0000</p></div>
        </div>
        <div class="pub-footer-bottom">
            <span>© {{ date('Y') }} INSTITUSI. Hak cipta dilindungi.</span>
            <span>Website resmi · Indonesia</span>
        </div>
    </div>
</footer>
