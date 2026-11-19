<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark">
    <link rel="icon" type="image/png" href="{{ asset('logo-bimas-katolik.png') }}?v=1">
    <link rel="apple-touch-icon" href="{{ asset('logo-bimas-katolik.png') }}?v=1">
    <title>@yield('title', 'Pendataan Guru') — {{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }}</title>
    <meta name="description" content="@yield('meta_description', 'Pendataan guru penerima TPG Kemenag Kementerian Agama Kab.TTS — Formulir Pendataan Guru Sertifikasi resmi.')">
    <meta name="theme-color" content="#0b0e14">

    <!-- Social Media Sharing Meta Tags (WhatsApp, Facebook, Telegram, Twitter) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Pendataan guru penerima TPG Kemenag Kementerian Agama Kab.TTS">
    <meta property="og:description" content="Portal Resmi Formulir Pendataan Guru Penerima TPG Kantor Kementerian Agama Kabupaten Timor Tengah Selatan.">
    <meta property="og:image" content="{{ asset('logo-bimas-katolik.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('logo-bimas-katolik.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="600">
    <meta property="og:site_name" content="Kementerian Agama Kab.TTS">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Pendataan guru penerima TPG Kemenag Kementerian Agama Kab.TTS">
    <meta name="twitter:description" content="Portal Resmi Formulir Pendataan Guru Penerima TPG Kantor Kementerian Agama Kabupaten Timor Tengah Selatan.">
    <meta name="twitter:image" content="{{ asset('logo-bimas-katolik.png') }}">

    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body id="top" class="public-body premium-body">
    @include('partials.public-navbar')

    <main id="main-content">
        @if (session('success'))
            <div class="pub-container" style="padding-top: 24px;">
                <div class="pd-alert pd-alert-success" style="border-radius: 12px; margin-bottom: 0;">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="pub-container" style="padding-top: 24px;">
                <div class="pd-alert pd-alert-error" style="border-radius: 12px; margin-bottom: 0;">{{ session('error') }}</div>
            </div>
        @endif
        @if (session('warning'))
            <div class="pub-container" style="padding-top: 24px;">
                <div class="pd-alert" style="color: #ffd280; border: 1px solid rgba(245,154,69,.25); background: rgba(245,154,69,.08); border-radius: 12px; margin-bottom: 0;">{{ session('warning') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.public-footer')
    <button
        type="button"
        class="pub-back-to-top"
        x-cloak
        x-show="showBackToTop"
        @scroll.window="showBackToTop = window.scrollY > 300"
        @click="window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })"
        aria-label="Kembali ke atas"
        title="Kembali ke atas">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m18 15-6-6-6 6"/><path d="M12 9v12"/></svg>
    </button>
    @stack('scripts')
    @if (session('success'))
        <script>
            window.addEventListener('load', () => {
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'success',
                        title: 'Pendataan berhasil dikirim',
                        text: @json(session('success')),
                        confirmButtonText: 'Baik, saya mengerti',
                        confirmButtonColor: '#07823f',
                    });
                }
            });
        </script>
    @endif
</body>
</html>
