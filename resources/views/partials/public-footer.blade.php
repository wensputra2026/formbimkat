<footer class="pub-footer">
    <div class="pub-container">
        <div class="pub-footer-grid">
            <div class="pub-footer-about">
                <a href="{{ route('guru.create') }}" class="pub-brand pub-brand-footer">
                    <span class="pub-brand-mark">
                        <svg style="width: 20px; height: 20px; color: #28170b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </span>
                    <span class="pub-brand-text">
                        <strong>{{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }}</strong>
                        <small>{{ \App\Models\Setting::get('instansi', 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}</small>
                    </span>
                </a>
                <p>{{ \App\Models\Setting::get('alamat_instansi', 'Kantor Kementerian Agama. Melayani dengan integritas, akurasi data, dan transparansi data guru.') }}</p>
            </div>
            <div>
                <h3>Layanan Pendataan</h3>
                <a href="{{ route('guru.create') }}">Formulir Pendataan Guru</a>
                <a href="{{ route('guru.cek_status') }}">Cek Status Registrasi</a>
            </div>
            <div>
                <h3>Akses Sistem</h3>
                <a href="{{ route('login') }}">Login Administrator</a>
            </div>
            <div>
                <h3>Kontak & Layanan</h3>
                <p>{{ \App\Models\Setting::get('alamat_instansi', 'Kementerian Agama Republik Indonesia') }}</p>
                <p>Email: {{ \App\Models\Setting::get('email', 'pendataan@kemenag.go.id') }}</p>
                @if(\App\Models\Setting::get('kontak'))
                    <p>Kontak: {{ \App\Models\Setting::get('kontak') }}</p>
                @endif
            </div>
        </div>
        <div class="pub-footer-bottom">
            <span>© {{ \App\Models\Setting::get('tahun_pendataan', date('Y')) }} {{ \App\Models\Setting::get('instansi', 'Kementerian Agama') }}. Seluruh hak cipta dilindungi.</span>
            <span>Sistem Informasi Pendataan Guru</span>
        </div>
    </div>
</footer>
