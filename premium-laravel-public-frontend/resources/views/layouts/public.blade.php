<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="publicSite()" x-init="initTheme()" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — INSTITUSI</title>
    <meta name="description" content="@yield('meta_description', 'Website resmi institusi. Informasi, layanan, berita, dan publikasi.')">
    <meta name="theme-color" content="#f8fafc">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="public-body">
    @include('partials.public-navbar')
    <main id="main-content">
        @yield('content')
    </main>
    @include('partials.public-footer')
    @stack('scripts')
</body>
</html>
