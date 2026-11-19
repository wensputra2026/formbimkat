<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#0b0e14">
    <link rel="icon" type="image/png" href="{{ asset('logo-bimas-katolik.png') }}?v=1">
    <link rel="apple-touch-icon" href="{{ asset('logo-bimas-katolik.png') }}?v=1">
    <title>@yield('title', 'Dashboard') — {{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }}</title>
    <meta property="og:title" content="Pendataan guru penerima TPG Kemenag Kementerian Agama Kab.TTS">
    <meta property="og:description" content="Portal Resmi Formulir Pendataan Guru Penerima TPG Kantor Kementerian Agama Kabupaten Timor Tengah Selatan.">
    <meta property="og:image" content="{{ asset('logo-bimas-katolik.png') }}">
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')

    @php
        $currentRoute = request()->route()?->getName();
        $currentUrl = request()->getRequestUri();
        $activeGroupSlugs = [];

        foreach (config('premium-navigation.navigation', []) as $navGroup) {
            foreach ($navGroup['items'] ?? [] as $navItem) {
                $children = collect($navItem['children'] ?? []);
                if ($children->isNotEmpty()) {
                    $hasActiveChild = $children->contains(function ($child) use ($currentRoute, $currentUrl) {
                        if (isset($child['route']) && $currentRoute === $child['route']) return true;
                        if (isset($child['url']) && ($currentUrl === $child['url'] || (str_contains($currentUrl, $child['url']) && $child['url'] !== '/admin'))) return true;
                        return false;
                    });

                    if (!$hasActiveChild && \Illuminate\Support\Str::slug($navItem['label']) === 'data-guru') {
                        if (str_starts_with((string)$currentRoute, 'admin.guru.')) {
                            $hasActiveChild = true;
                        }
                    }

                    if ($hasActiveChild) {
                        $activeGroupSlugs[] = \Illuminate\Support\Str::slug($navItem['label']);
                    }
                }
            }
        }
    @endphp
</head>
<body class="premium-body" x-data="premiumDashboard(@js($activeGroupSlugs))" :class="{ 'sidebar-collapsed': collapsed }">
    <div class="pd-shell">
        <div class="pd-mobile-backdrop" x-show="mobileOpen" x-transition.opacity
             @click="mobileOpen = false" x-cloak></div>

        @include('partials.sidebar')

        <main class="pd-main">
            @include('partials.topbar')

            <div class="pd-content">
                @if (session('success'))
                    <div class="pd-alert pd-alert-success">{{ session('success') }}</div>
                    <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('success')), confirmButtonColor: '#07823f' }));</script>
                @endif
                @if (session('error'))
                    <div class="pd-alert pd-alert-error">{{ session('error') }}</div>
                @endif
                @if (session('warning'))
                    <div class="pd-alert" style="color: #ffd280; border: 1px solid rgba(245,154,69,.25); background: rgba(245,154,69,.08);">{{ session('warning') }}</div>
                @endif

                @yield('content')
            </div>

            <footer class="pd-footer">
                <span>© {{ date('Y') }} {{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }} — {{ \App\Models\Setting::get('instansi', 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}</span>
                <span>Sistem Informasi Pendataan Guru Kemenag</span>
            </footer>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
