@php($isAdminLoggedIn = auth()->check())
<header class="pub-header" x-data="{ menuOpen: false }">
    <div class="pub-container pub-nav-wrap">
        <a href="{{ route('guru.create') }}" class="pub-brand" aria-label="Beranda Kementerian Agama">
            <span class="pub-brand-mark pub-brand-logo" style="width: 38px; height: 38px; flex: 0 0 38px; border-radius: 10px;">
                <img src="{{ asset('logo-bimas-katolik.png') }}" alt="Logo Kementerian Agama" width="38" height="38">
            </span>
            <span class="pub-brand-text">
                <strong style="color: #0f172a;">{{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }}</strong>
                <small style="color: #64748b;">{{ \App\Models\Setting::get('instansi', 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}</small>
            </span>
        </a>

        <button class="pub-mobile-toggle" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen" aria-label="Buka navigasi">
            <span x-show="!menuOpen" style="font-size: 20px; line-height: 1;">☰</span>
            <span x-show="menuOpen" x-cloak style="font-size: 20px; line-height: 1;">✕</span>
        </button>

        <nav class="pub-nav" :class="{ 'is-open': menuOpen }" aria-label="Navigasi utama">
            <a href="{{ route('guru.create') }}" class="{{ request()->routeIs('guru.create') ? 'is-active' : '' }}">Formulir Pendataan</a>
            <a href="{{ route('guru.cek_status') }}" class="{{ request()->routeIs('guru.cek_status') ? 'is-active' : '' }}">Cek Status</a>
            <div class="pub-mobile-actions">
                @if($isAdminLoggedIn)
                    <a href="{{ route('admin.dashboard') }}" class="pub-button pub-button-primary">Panel Admin</a>
                @else
                    <a href="{{ route('login') }}" class="pub-button pub-button-primary">Login Admin</a>
                @endif
            </div>
        </nav>

        <div class="pub-nav-actions">
            @if($isAdminLoggedIn)
                <a href="{{ route('admin.dashboard') }}" class="pub-button pub-button-primary">Panel Admin</a>
            @else
                <a href="{{ route('login') }}" class="pub-button pub-button-primary">Login Admin</a>
            @endif
        </div>
    </div>
</header>
