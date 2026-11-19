<header class="pd-topbar">
    <div class="pd-topbar-left">
        <button class="pd-icon-button pd-mobile-menu" @click="mobileOpen = true" aria-label="Buka menu">☰</button>
        <button class="pd-icon-button pd-collapse-button" @click="collapsed = !collapsed" :title="collapsed ? 'Perluas sidebar' : 'Ringkas sidebar'">
            <span x-text="collapsed ? '⇥' : '⇤'"></span>
        </button>
        <div class="pd-breadcrumb">
            <span>Workspace</span><span class="pd-breadcrumb-sep">/</span><strong>@yield('page-title', 'Dashboard')</strong>
        </div>
    </div>
    <div class="pd-topbar-actions">
        <div class="pd-status"><span class="pd-status-dot"></span> Sistem normal</div>
        <button class="pd-icon-button pd-notification" aria-label="Notifikasi">♧<i></i></button>
        <div class="pd-topbar-divider"></div>
        <button class="pd-profile-mini">
            <span class="pd-avatar pd-avatar-small">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
            <span>{{ auth()->user()->name ?? 'Administrator' }}</span>
            <span class="pd-chevron">⌄</span>
        </button>
    </div>
</header>
