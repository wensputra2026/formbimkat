<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" type="image/png" href="{{ asset('logo-bimas-katolik.png') }}?v=1">
    <link rel="apple-touch-icon" href="{{ asset('logo-bimas-katolik.png') }}?v=1">
    <title>Login Administrator — {{ \App\Models\Setting::get('app_name', 'Pendataan Guru Kemenag') }}</title>
    
    <!-- Social Share Meta -->
    <meta property="og:title" content="Pendataan guru penerima TPG Kemenag Kementerian Agama Kab.TTS">
    <meta property="og:image" content="{{ asset('logo-bimas-katolik.png') }}">

    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="premium-body" style="background: #f8fafc; min-height: 100vh; display: grid; place-items: center; padding: 24px 16px;">

    <div style="width: 100%; max-width: 440px; display: flex; flex-direction: column; gap: 24px;">
        
        <!-- Logo & Title -->
        <div style="text-align: center;">
            <div style="width: 68px; height: 68px; margin: 0 auto 16px; padding: 6px; border-radius: 16px; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06); display: grid; place-items: center;">
                <img src="{{ asset('logo-bimas-katolik.png') }}" alt="Logo Kementerian Agama" style="display: block; width: 100%; height: 100%; object-fit: contain;">
            </div>
            
            <span style="display: inline-block; padding: 4px 14px; border-radius: 9999px; font-size: 11px; font-weight: 800; letter-spacing: 0.1em; background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; text-transform: uppercase;">
                PORTAL KHUSUS ADMINISTRATOR
            </span>

            <h1 style="margin: 12px 0 0; font-size: clamp(22px, 3.5vw, 26px); font-weight: 800; color: #0f172a; letter-spacing: -0.02em; line-height: 1.3;">
                {{ \App\Models\Setting::get('app_name', 'Pendataan Guru BIMAS Katolik') }}
            </h1>
            
            <p style="margin: 6px 0 0; font-size: 13px; color: #475569; font-weight: 500; line-height: 1.5;">
                {{ \App\Models\Setting::get('instansi', 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}
            </p>
        </div>

        <!-- Login Panel -->
        <div class="pd-panel" style="padding: clamp(24px, 5vw, 36px); background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);">
            @if(session('success'))
                <div class="pd-alert pd-alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="pd-alert pd-alert-error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf

                <div>
                    <label for="email" class="pd-label" style="color: #0f172a; font-weight: 700;">Email Administrator</label>
                    <input type="email" name="email" id="email" value="{{ old('email', 'admin@kemenag.go.id') }}" required autofocus placeholder="admin@kemenag.go.id" class="pd-input" style="background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                </div>

                <div>
                    <label for="password" class="pd-label" style="color: #0f172a; font-weight: 700;">Kata Sandi</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" required placeholder="Masukkan kata sandi" class="pd-input" style="padding-right: 46px; background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                        <button type="button" aria-label="Tampilkan atau sembunyikan kata sandi" title="Tampilkan atau sembunyikan kata sandi" onclick="const field = document.getElementById('password'); field.type = field.type === 'password' ? 'text' : 'password';" style="position: absolute; top: 50%; right: 10px; display: grid; width: 32px; height: 32px; transform: translateY(-50%); place-items: center; color: #64748b; border: 0; border-radius: 7px; background: transparent; cursor: pointer;">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                    <label style="display: flex; align-items: center; gap: 7px; cursor: pointer; color: #475569; font-weight: 500;">
                        <input type="checkbox" name="remember" style="accent-color: #ea580c; width: 16px; height: 16px;">
                        <span>Ingat sesi saya</span>
                    </label>
                    <span style="color: #64748b; font-size: 11.5px;">Hanya Akun Admin</span>
                </div>

                <button type="submit" class="pub-button pub-button-primary pub-button-large" style="width: 100%; font-size: 14px; font-weight: 800; margin-top: 4px;">
                    <span>MASUK KE PANEL ADMIN</span>
                    <span>→</span>
                </button>
            </form>

            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #f1f5f9; text-align: center;">
                <a href="{{ route('guru.create') }}" style="color: #ea580c; font-size: 13px; font-weight: 600; text-decoration: none;">
                    ← Kembali ke Formulir Guru
                </a>
            </div>
        </div>

        <p style="text-align: center; font-size: 12px; color: #64748b; margin: 0; line-height: 1.5;">
            Aktivitas login dicatat secara otomatis dalam audit riwayat IP kepegawaian.
        </p>
    </div>
</body>
</html>
