@php
    $navGroups = config('premium-navigation.navigation', []);
    $currentRoute = request()->route()?->getName();

    $canSee = function (array $item): bool {
        if (empty($item['roles'])) return true;
        $roles = $item['roles'];

        if (function_exists('auth') && auth()->check()) {
            $user = auth()->user();
            if (method_exists($user, 'hasAnyRole')) return $user->hasAnyRole($roles);
            if (method_exists($user, 'hasRole')) {
                foreach ($roles as $role) if ($user->hasRole($role)) return true;
                return false;
            }
        }
        // Fail closed for role-restricted links when no role integration exists.
        return false;
    };

    $iconSvg = function ($name) {
        $icons = [
            'layout-dashboard' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>',
            'database' => '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/>',
            'files' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8"/>',
            'tags' => '<path d="M20 13 13 20 3 10V3h7z"/><circle cx="7.5" cy="7.5" r="1"/>',
            'folder-kanban' => '<path d="M3 6a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M8 11v6M12 11v3M16 11v5"/>',
            'list' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
            'plus-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/>',
            'chart-no-axes-combined' => '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-5 5"/><path d="M15 9h4v4"/>',
            'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
            'user-round' => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
            'shield-check' => '<path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11"/><path d="m9 12 2 2 4-4"/>',
            'settings' => '<circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.7-1.4-2.4 1.4-1.1a8 8 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.7 1.4 2.4-1.4 1.1a8 8 0 0 1-.1 2Z"/>',
        ];
        return $icons[$name] ?? '<circle cx="12" cy="12" r="8"/>';
    };
@endphp

<aside class="pd-sidebar" :class="{ 'is-open': mobileOpen }">
    <div class="pd-brand">
        <div class="pd-brand-mark">{{ config('premium-navigation.brand.short', 'PA') }}</div>
        <div class="pd-brand-copy">
            <strong>{{ config('premium-navigation.brand.name') }}</strong>
            <small>{{ config('premium-navigation.brand.description') }}</small>
        </div>
        <button class="pd-icon-button pd-sidebar-close" @click="mobileOpen = false" aria-label="Tutup menu">×</button>
    </div>

    <div class="pd-sidebar-search">
        <span>⌕</span>
        <input type="search" placeholder="Cari menu..." x-model="menuSearch" aria-label="Cari menu">
        <kbd>⌘ K</kbd>
    </div>

    <nav class="pd-nav" aria-label="Navigasi utama">
        @foreach ($navGroups as $group)
            @php
                $visibleItems = collect($group['items'] ?? [])->filter(fn ($item) => $canSee($item));
            @endphp
            @if ($visibleItems->isNotEmpty())
                <div class="pd-nav-section">
                    <div class="pd-nav-heading">{{ $group['section'] ?? 'MENU' }}</div>
                    @foreach ($visibleItems as $item)
                        @php
                            $children = collect($item['children'] ?? [])->filter(fn ($child) => $canSee($child));
                            $isActive = isset($item['route']) && $currentRoute === $item['route'];
                            $isChildActive = $children->contains(fn ($child) => isset($child['route']) && $currentRoute === $child['route']);
                            $targetUrl = isset($item['url']) ? $item['url'] : (isset($item['route']) && \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : '#');
                        @endphp

                        @if ($children->isNotEmpty())
                            <div class="pd-nav-parent" x-show="matchesMenu(@js($item['label']), @js($children->pluck('label')->all()))">
                                <button class="pd-nav-link {{ $isChildActive ? 'active' : '' }}"
                                        @click="toggleGroup(@js(\Illuminate\Support\Str::slug($item['label'])))"
                                        :aria-expanded="isGroupOpen(@js(\Illuminate\Support\Str::slug($item['label'])))">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">{!! $iconSvg($item['icon'] ?? '') !!}</svg>
                                    <span class="pd-nav-label">{{ $item['label'] }}</span>
                                    <span class="pd-chevron">⌄</span>
                                </button>
                                <div class="pd-subnav" x-show="isGroupOpen(@js(\Illuminate\Support\Str::slug($item['label'])))" x-collapse>
                                    @foreach ($children as $child)
                                        @php
                                            $childUrl = isset($child['url']) ? $child['url'] : (isset($child['route']) && \Illuminate\Support\Facades\Route::has($child['route']) ? route($child['route']) : '#');
                                            $childActive = isset($child['route']) && $currentRoute === $child['route'];
                                        @endphp
                                        <a href="{{ $childUrl }}" class="pd-subnav-link {{ $childActive ? 'active' : '' }}">
                                            <span class="pd-subnav-dot"></span>{{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $targetUrl }}" class="pd-nav-link {{ $isActive ? 'active' : '' }}"
                               x-show="matchesMenu(@js($item['label']), [])">
                                <svg viewBox="0 0 24 24" aria-hidden="true">{!! $iconSvg($item['icon'] ?? '') !!}</svg>
                                <span class="pd-nav-label">{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        @endforeach
    </nav>

    <div class="pd-sidebar-bottom">
        <div class="pd-help-card">
            <div class="pd-help-icon">✦</div>
            <strong>Butuh bantuan?</strong>
            <span>Pusat bantuan aplikasi</span>
            <a href="#" class="pd-help-link">Lihat panduan <span>→</span></a>
        </div>
        <div class="pd-user-card">
            <div class="pd-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div class="pd-user-copy">
                <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong>
                <small>{{ auth()->user()->email ?? 'admin@example.com' }}</small>
            </div>
            <button class="pd-icon-button" title="Menu akun">···</button>
        </div>
    </div>
</aside>
