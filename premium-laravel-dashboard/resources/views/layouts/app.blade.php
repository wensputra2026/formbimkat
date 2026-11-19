<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('premium-navigation.brand.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="premium-body" x-data="premiumDashboard()" :class="{ 'sidebar-collapsed': collapsed }">
    <div class="pd-shell">
        <div class="pd-mobile-backdrop" x-show="mobileOpen" x-transition.opacity
             @click="mobileOpen = false" x-cloak></div>

        @include('partials.sidebar')

        <main class="pd-main">
            @include('partials.topbar')

            <div class="pd-content">
                @if (session('success'))
                    <div class="pd-alert pd-alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="pd-alert pd-alert-error">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>

            <footer class="pd-footer">
                <span>© {{ date('Y') }} {{ config('premium-navigation.brand.name') }}</span>
                <span>Premium Admin Workspace</span>
            </footer>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
