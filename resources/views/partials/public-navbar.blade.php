@php($isAdminLoggedIn = auth()->check())
<header class="pub-header" x-data="{ menuOpen: false }">
    <div class="pub-container pub-nav-wrap">
        <a href="{{ route('guru.create') }}" class="pub-brand" aria-label="Beranda Kementerian Agama">
            <span class="pub-brand-mark pub-brand-logo">
                <img src="{{ asset('logo-bimas-katolik.png') }}" alt="Logo Kementerian Agama" width="48" height="48">
            </span>
            <span class="pub-brand-text">
                <strong>{{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }}</strong>
                <small>{{ \App\Models\Setting::get('instansi', 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}</small>
            </span>
        </a>

        <button class="pub-mobile-toggle" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen" aria-label="Buka navigasi">
            <span x-show="!menuOpen">☰</span><span x-show="menuOpen" x-cloak>✕</span>
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
