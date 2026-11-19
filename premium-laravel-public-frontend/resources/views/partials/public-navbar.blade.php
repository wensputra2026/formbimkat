<header class="pub-header" x-data="{ menuOpen: false, servicesOpen: false }">
    <div class="pub-container pub-nav-wrap">
        <a href="{{ route('public.home') }}" class="pub-brand" aria-label="Beranda INSTITUSI">
            <span class="pub-brand-mark">I</span>
            <span class="pub-brand-text"><strong>INSTITUSI</strong><small>INFORMASI RESMI</small></span>
        </a>

        <button class="pub-mobile-toggle" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen" aria-label="Buka navigasi">
            <span x-show="!menuOpen">☰</span><span x-show="menuOpen" x-cloak>✕</span>
        </button>

        <nav class="pub-nav" :class="{ 'is-open': menuOpen }" aria-label="Navigasi utama">
            <a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'is-active' : '' }}">Beranda</a>
            <a href="{{ route('public.about') }}" class="{{ request()->routeIs('public.about') ? 'is-active' : '' }}">Profil</a>
            <a href="{{ route('public.services') }}" class="{{ request()->routeIs('public.services') ? 'is-active' : '' }}">Layanan</a>
            <a href="{{ route('public.news') }}" class="{{ request()->routeIs('public.news') ? 'is-active' : '' }}">Berita</a>
            <a href="{{ route('public.contact') }}" class="{{ request()->routeIs('public.contact') ? 'is-active' : '' }}">Kontak</a>
            <div class="pub-mobile-actions">
                <button class="pub-theme-toggle" @click="$dispatch('toggle-theme')"><span x-text="darkMode ? '☼ Mode terang' : '☾ Mode gelap'"></span></button>
                <a href="{{ url('/login') }}" class="pub-button pub-button-primary">Masuk <span>→</span></a>
            </div>
        </nav>

        <div class="pub-nav-actions">
            <button class="pub-theme-toggle pub-desktop-theme" @click="$dispatch('toggle-theme')" :aria-label="darkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'">
                <span x-text="darkMode ? '☼' : '☾'"></span>
            </button>
            <a href="{{ url('/login') }}" class="pub-button pub-button-primary">Masuk <span>→</span></a>
        </div>
    </div>
</header>
