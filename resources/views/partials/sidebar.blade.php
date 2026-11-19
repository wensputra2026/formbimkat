@php
    $navGroups = config('premium-navigation.navigation', []);
    $currentRoute = request()->route()?->getName();
    $currentUrl = request()->getRequestUri();

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
    <!-- Brand -->
    <div class="pd-brand">
        <div class="pd-brand-mark" style="padding: 3px; overflow: hidden; background-color: #fff !important; box-shadow: none;"><img src="{{ asset('logo-bimas-katolik.png') }}" alt="Logo Kementerian Agama" style="display: block; width: 100%; height: 100%; object-fit: contain; border-radius: 8px;"></div>
        <div class="pd-brand-copy">
            <strong>{{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }}</strong>
            <small>{{ \App\Models\Setting::get('instansi', 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}</small>
        </div>
        <button class="pd-icon-button pd-sidebar-close" @click="mobileOpen = false" aria-label="Tutup menu">×</button>
    </div>

    <!-- Navigation List -->
    <nav class="pd-nav" aria-label="Navigasi utama">
        @foreach ($navGroups as $group)
            <div class="pd-nav-section">
                @foreach ($group['items'] ?? [] as $item)
                    @php
                        $children = collect($item['children'] ?? []);
                        $isActive = isset($item['route']) && $currentRoute === $item['route'];
                        $isChildActive = $children->contains(function ($child) use ($currentRoute, $currentUrl) {
                            if (isset($child['route']) && $currentRoute === $child['route']) return true;
                            if (isset($child['url']) && ($currentUrl === $child['url'] || (str_contains($currentUrl, $child['url']) && $child['url'] !== '/admin'))) return true;
                            return false;
                        });
                        if (!$isChildActive && \Illuminate\Support\Str::slug($item['label']) === 'data-guru' && str_starts_with((string)$currentRoute, 'admin.guru.')) {
                            $isChildActive = true;
                        }
                        $targetUrl = isset($item['url']) ? $item['url'] : (isset($item['route']) && \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : '#');
                    @endphp

                    @if ($children->isNotEmpty())
                        <div class="pd-nav-parent" x-show="matchesMenu(@js($item['label']), @js($children->pluck('label')->all()))">
                            <button type="button"
                                    class="pd-nav-link {{ $isChildActive ? 'active' : '' }}"
                                    @click="toggleGroup(@js(\Illuminate\Support\Str::slug($item['label'])))"
                                    :aria-expanded="isGroupOpen(@js(\Illuminate\Support\Str::slug($item['label'])))">
                                <svg class="pd-nav-icon" viewBox="0 0 24 24" aria-hidden="true">{!! $iconSvg($item['icon'] ?? '') !!}</svg>
                                <span class="pd-nav-label">{{ $item['label'] }}</span>
                                <svg class="pd-chevron"
                                     :class="{ 'rotate-180': isGroupOpen(@js(\Illuminate\Support\Str::slug($item['label']))) }"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2.2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>
                            <div class="pd-subnav" x-show="isGroupOpen(@js(\Illuminate\Support\Str::slug($item['label'])))" x-collapse>
                                @foreach ($children as $child)
                                    @php
                                        $childUrl = isset($child['url']) ? $child['url'] : (isset($child['route']) && \Illuminate\Support\Facades\Route::has($child['route']) ? route($child['route']) : '#');
                                        $childActive = false;
                                        if (isset($child['route']) && $currentRoute === $child['route']) {
                                            $childActive = true;
                                        } elseif (isset($child['url'])) {
                                            $currentQuery = request()->getQueryString();
                                            $childQuery = parse_url($child['url'], PHP_URL_QUERY);
                                            if ($childQuery) {
                                                $childActive = ($currentUrl === $child['url']) || (str_contains($currentUrl, $child['url']));
                                            } else {
                                                $childActive = ($currentUrl === $child['url']);
                                            }
                                        }
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
                            <svg class="pd-nav-icon" viewBox="0 0 24 24" aria-hidden="true">{!! $iconSvg($item['icon'] ?? '') !!}</svg>
                            <span class="pd-nav-label">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        @endforeach
    </nav>

    <!-- Bottom User Bar -->
    <div class="pd-sidebar-bottom">
        <div class="pd-user-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 9px; min-width: 0;">
                <span class="pd-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                <div class="pd-user-copy">
                    <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong>
                    <small>{{ auth()->user()->email ?? 'admin@kemenag.go.id' }}</small>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" data-swal-title="Keluar dari aplikasi?" data-swal-confirm="Anda akan mengakhiri sesi admin saat ini." data-swal-icon="question" data-swal-confirm-button="Ya, keluar" style="margin: 0;">
                @csrf
                <button type="submit" class="pd-icon-button" title="Keluar / Logout" style="color: #f87171;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
