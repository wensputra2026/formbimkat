<footer class="pub-footer">
    <div class="pub-container">
        <div class="pub-footer-grid">
            <div class="pub-footer-about">
                <a href="{{ route('guru.create') }}" class="pub-brand" style="margin-bottom: 8px;">
                    <span class="pub-brand-mark pub-brand-logo" style="width: 38px; height: 38px; flex: 0 0 38px; border-radius: 10px;">
                        <img src="{{ asset('logo-bimas-katolik.png') }}" alt="Logo Kementerian Agama" width="38" height="38">
                    </span>
                    <span class="pub-brand-text">
                        <strong style="color: #0f172a; font-size: 14px;">{{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }}</strong>
                        <small style="color: #64748b; font-size: 10px;">{{ \App\Models\Setting::get('instansi', 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}</small>
                    </span>
                </a>
                <p style="color: #64748b; line-height: 1.7; font-size: 12.5px; margin-top: 12px;">{{ \App\Models\Setting::get('alamat_instansi', 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan. Melayani dengan integritas, akurasi data, dan transparansi data guru.') }}</p>
            </div>
            <div>
                <h3 style="color: #0f172a; font-size: 12.5px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 14px;">Layanan Pendataan</h3>
                <a href="{{ route('guru.create') }}" style="color: #334155; display: block; margin: 8px 0; font-size: 13px;">Formulir Pendataan Guru</a>
                <a href="{{ route('guru.cek_status') }}" style="color: #334155; display: block; margin: 8px 0; font-size: 13px;">Cek Status Registrasi</a>
            </div>
            <div>
                <h3 style="color: #0f172a; font-size: 12.5px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 14px;">Akses Sistem</h3>
                <a href="{{ route('login') }}" style="color: #334155; display: block; margin: 8px 0; font-size: 13px;">Login Administrator</a>
            </div>
            <div>
                <h3 style="color: #0f172a; font-size: 12.5px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 14px;">Kontak & Layanan</h3>
                <p style="color: #64748b; font-size: 12.5px; margin: 6px 0; line-height: 1.6;">{{ \App\Models\Setting::get('alamat_instansi', 'Kementerian Agama Republik Indonesia') }}</p>
                <p style="color: #64748b; font-size: 12.5px; margin: 6px 0;">Email: <span style="color: #ea580c; font-weight: 600;">{{ \App\Models\Setting::get('email', 'pendataan@kemenag.go.id') }}</span></p>
                @if(\App\Models\Setting::get('kontak'))
                    <p style="color: #64748b; font-size: 12.5px; margin: 6px 0;">Kontak: <span style="color: #ea580c; font-weight: 600;">{{ \App\Models\Setting::get('kontak') }}</span></p>
                @endif
            </div>
        </div>
        <div class="pub-footer-bottom" style="border-top: 1px solid #f1f5f9; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; color: #94a3b8; font-size: 12px;">
            <span>© {{ \App\Models\Setting::get('tahun_pendataan', date('Y')) }} {{ \App\Models\Setting::get('instansi', 'Kementerian Agama') }}. Seluruh hak cipta dilindungi.</span>
            <span style="color: #475569; font-weight: 600;">Sistem Informasi Pendataan Guru</span>
        </div>
    </div>
</footer>
