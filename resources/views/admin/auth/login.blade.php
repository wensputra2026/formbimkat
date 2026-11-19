<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('logo-bimas-katolik.png') }}?v=1">
    <link rel="apple-touch-icon" href="{{ asset('logo-bimas-katolik.png') }}?v=1">
    <title>Login Administrator — {{ \App\Models\Setting::get('app_name', 'Pendataan Guru Kemenag') }}</title>
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="premium-body" style="background: #064e2b; min-height: 100vh; display: grid; place-items: center; padding: 20px;">

    <div style="width: 100%; max-width: 420px; display: flex; flex-direction: column; gap: 24px;">
        
        <!-- Logo & Title -->
        <div style="text-align: center;">
            <div class="pd-brand-mark" style="width: 64px; height: 64px; margin: 0 auto 14px; padding: 4px; overflow: hidden; background-color: #fff !important; box-shadow: 0 4px 14px rgb(15 23 42 / 12%);"><img src="{{ asset('logo-bimas-katolik.png') }}" alt="Logo Kementerian Agama" style="display: block; width: 100%; height: 100%; object-fit: contain; border-radius: 8px;"></div>
            <span class="pd-eyebrow" style="margin: 0; font-size: 10px;">PORTAL KHUSUS ADMINISTRATOR</span>
            <h1 style="margin: 6px 0 0; font-size: 24px; font-weight: 800; color: #f4f5f7;">
                {{ \App\Models\Setting::get('app_name', 'Pendataan Guru.') }}
            </h1>
            <p style="margin: 4px 0 0; font-size: 12px; color: var(--pd-muted);">
                {{ \App\Models\Setting::get('instansi', 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan') }}
            </p>
        </div>

        <!-- Login Panel -->
        <div class="pd-panel" style="padding: clamp(24px, 5vw, 36px);">
            @if(session('success'))
                <div class="pd-alert pd-alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="pd-alert pd-alert-error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 18px;">
                @csrf

                <div>
                    <label for="email" class="pd-label">Email Administrator</label>
                    <input type="email" name="email" id="email" value="{{ old('email', 'admin@kemenag.go.id') }}" required autofocus placeholder="admin@kemenag.go.id" class="pd-input">
                </div>

                <div>
                    <label for="password" class="pd-label">Kata Sandi</label>
                    <div style="position: relative;">
    <input type="password" name="password" id="password" required placeholder="Kata sandi" class="pd-input" style="padding-right: 46px;">
    <button type="button" aria-label="Tampilkan atau sembunyikan kata sandi" title="Tampilkan atau sembunyikan kata sandi" onclick="const field = document.getElementById('password'); field.type = field.type === 'password' ? 'text' : 'password';" style="position: absolute; top: 50%; right: 10px; display: grid; width: 32px; height: 32px; transform: translateY(-50%); place-items: center; color: #64748b; border: 0; border-radius: 7px; background: transparent; cursor: pointer;">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
    </button>
</div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--pd-muted);">
                        <input type="checkbox" name="remember" style="accent-color: var(--pd-accent);">
                        <span>Ingat sesi saya</span>
                    </label>
                    <span style="color: var(--pd-dim);">Hanya Akun Admin</span>
                </div>

                <button type="submit" class="pd-button pd-button-primary" style="height: 44px; font-weight: 800; width: 100%; margin-top: 6px;">
                    <span>MASUK KE PANEL ADMIN</span>
                    <span>→</span>
                </button>
            </form>

            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--pd-border); text-align: center;">
                <a href="{{ route('guru.create') }}" style="color: var(--pd-accent-2); font-size: 11px; font-weight: 600; text-decoration: none;">
                    ← Kembali ke Formulir Guru
                </a>
            </div>
        </div>

        <p style="text-align: center; font-size: 11px; color: var(--pd-dim); margin: 0;">
            Aktivitas login dicatat secara otomatis dalam audit riwayat IP kepegawaian.
        </p>
    </div>
</body>
</html>
