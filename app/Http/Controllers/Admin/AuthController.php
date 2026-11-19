<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LoginHistory;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $appName = Setting::get('app_name', 'Pendataan Guru Kementerian Agama');
        $instansi = Setting::get('instansi', 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan');

        return view('admin.auth.login', compact('appName', 'instansi'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $browser = LoginHistory::detectBrowser($userAgent);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout();
                LoginHistory::create([
                    'username' => $credentials['email'],
                    'user_id' => $user->id,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'browser' => $browser,
                    'status' => 'failed',
                    'failure_reason' => 'Bukan akun administrator.',
                    'login_at' => now(),
                ]);

                return back()->withErrors(['email' => 'Akun Anda tidak memiliki hak akses administrator.'])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Catat history login berhasil
            $history = LoginHistory::create([
                'username' => $user->email,
                'user_id' => $user->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'browser' => $browser,
                'status' => 'success',
                'failure_reason' => null,
                'login_at' => now(),
            ]);

            // Simpan history ID di session agar saat logout bisa diperbarui
            session(['current_login_history_id' => $history->id]);

            ActivityLog::log('login', "Admin {$user->name} berhasil login ke sistem.");

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Login berhasil. Selamat datang, {$user->name}.");
        }

        // Catat kegagalan login
        LoginHistory::create([
            'username' => $credentials['email'],
            'user_id' => null,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'browser' => $browser,
            'status' => 'failed',
            'failure_reason' => 'Kredensial email atau kata sandi tidak cocok.',
            'login_at' => now(),
        ]);

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $historyId = session('current_login_history_id');
            if ($historyId) {
                LoginHistory::where('id', $historyId)->update(['logout_at' => now()]);
            }

            ActivityLog::log('logout', "Admin {$user->name} logout dari sistem.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
