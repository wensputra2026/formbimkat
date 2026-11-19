<header class="pd-topbar">
    <div class="pd-topbar-left">
        <button class="pd-icon-button pd-mobile-menu" @click="mobileOpen = true" aria-label="Buka menu">☰</button>
        <button type="button" class="pd-icon-button pd-collapse-button" @click="collapsed = !collapsed" :title="collapsed ? 'Perluas sidebar' : 'Ringkas sidebar'" :aria-label="collapsed ? 'Perluas sidebar' : 'Ringkas sidebar'" :aria-expanded="!collapsed">
    <svg x-show="!collapsed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m14 7-5 5 5 5"/><path d="M20 5v14"/></svg>
    <svg x-show="collapsed" x-cloak width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m10 7 5 5-5 5"/><path d="M4 5v14"/></svg>
</button>
        <div class="pd-breadcrumb">
            <span>Admin Portal</span><span class="pd-breadcrumb-sep">/</span><strong>@yield('page-title', 'Dashboard')</strong>
        </div>
    </div>
    <div class="pd-topbar-actions">
        <div class="pd-status"><span class="pd-status-dot"></span> Server Aktif</div>
        <div class="pd-topbar-divider"></div>
        <a href="{{ route('guru.create') }}" target="_blank" class="pd-button pd-button-secondary" style="font-size: 11px; padding: 0 12px; height: 32px;">
            <span>↗ Formulir Publik</span>
        </a>
        <div class="pd-topbar-divider"></div>
        <div class="pd-profile-mini">
            <span class="pd-avatar pd-avatar-small">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
            <span>{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST" data-swal-title="Keluar dari aplikasi?" data-swal-confirm="Anda akan mengakhiri sesi admin saat ini." data-swal-icon="question" data-swal-confirm-button="Ya, keluar" style="margin: 0;">
            @csrf
            <button type="submit" class="pd-icon-button" title="Keluar / Logout" style="color: #f87171;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </div>
</header>
