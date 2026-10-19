<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $loginInput = $request->input('login')
            ?? $request->input('email')
            ?? $request->input('username');

        $identifier = trim((string) $loginInput);
        $password = (string) $request->input('password');

        if ($identifier === '' || $password === '') {
            $msg = 'Username / E-mail / NIP dan kata sandi wajib diisi.';
            throw ValidationException::withMessages([
                'login' => $msg,
                'email' => $msg,
                'username' => $msg,
            ]);
        }

        $throttleKey = Str::lower($identifier).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $msg = 'Terlalu banyak percobaan masuk. Coba lagi dalam '.$seconds.' detik.';
            throw ValidationException::withMessages([
                'login' => $msg,
                'email' => $msg,
                'username' => $msg,
            ]);
        }

        // Cari user berdasarkan username, email, NIP guru, atau NIS murid
        $user = User::query()
            ->where('username', $identifier)
            ->orWhere('email', $identifier)
            ->orWhereHas('teacher', fn ($q) => $q->where('nip', $identifier))
            ->orWhereHas('student', fn ($q) => $q->where('nis', $identifier))
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            $msg = 'Username / E-mail / NIP atau kata sandi salah.';
            throw ValidationException::withMessages([
                'login' => $msg,
                'email' => $msg,
                'username' => $msg,
            ]);
        }

        if (! $user->isActive()) {
            RateLimiter::hit($throttleKey, 60);

            $msg = 'Akun Anda dinonaktifkan. Silakan hubungi admin UKS.';
            throw ValidationException::withMessages([
                'login' => $msg,
                'email' => $msg,
                'username' => $msg,
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route($user->homeRoute()));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
