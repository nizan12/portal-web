<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses login — cek t_admin dulu, lalu t_pengguna (Hash::check dengan plaintext fallback).
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'NIK / Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $nip      = $request->username;
        $password = $request->password;

        // ── 1. Coba login sebagai Admin (t_admin) ──────────────────────────
        $admin = Admin::where('nik_admin', $nip)
            ->orWhere('username', $nip)
            ->first();

        if ($admin && (Hash::check($password, $admin->password) || $admin->password === $password)) {
            Auth::guard('admin')->login($admin, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // ── 2. Coba login sebagai Pengguna (t_pengguna) ────────────────────
        $pengguna = Pengguna::where('nik', $nip)
            ->orWhere('username', $nip)
            ->orWhere('email', $nip)
            ->first();

        if ($pengguna && (Hash::check($password, $pengguna->password) || $pengguna->password === $password)) {
            Auth::guard('pengguna')->login($pengguna, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('pengguna.dashboard');
        }

        // ── 3. Gagal ───────────────────────────────────────────────────────
        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => 'NIK / Username atau password salah.',
            ]);
    }

    /**
     * Logout semua guard.
     */
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('pengguna')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Tampilkan halaman lupa password.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim tautan reset password (Simulasi Interaktif).
     */
    public function handleForgotPassword(Request $request)
    {
        $request->validate([
            'identity' => ['required', 'string'],
        ], [
            'identity.required' => 'NIK atau Email wajib diisi.',
        ]);

        $identity = $request->identity;

        // Cek Admin (t_admin)
        $admin = Admin::where('nik_admin', $identity)
            ->orWhere('username', $identity)
            ->orWhere('email', $identity)
            ->first();

        if ($admin) {
            $token = \Illuminate\Support\Str::random(60);
            session([
                'reset_token' => $token,
                'reset_nik' => $admin->nik_admin,
                'reset_role' => 'admin'
            ]);

            return back()->with([
                'status' => 'Data Admin terverifikasi! Tautan reset password aman telah disiapkan.',
                'reset_token' => $token
            ]);
        }

        // Cek Pengguna (t_pengguna)
        $pengguna = Pengguna::where('nik', $identity)
            ->orWhere('username', $identity)
            ->orWhere('email', $identity)
            ->first();

        if ($pengguna) {
            $token = \Illuminate\Support\Str::random(60);
            session([
                'reset_token' => $token,
                'reset_nik' => $pengguna->nik,
                'reset_role' => 'pengguna'
            ]);

            return back()->with([
                'status' => 'Data Pengguna terverifikasi! Tautan reset password aman telah disiapkan.',
                'reset_token' => $token
            ]);
        }

        return back()->withErrors([
            'identity' => 'NIK atau Email tidak terdaftar dalam sistem.'
        ]);
    }

    /**
     * Tampilkan halaman atur ulang password.
     */
    public function showResetPassword($token)
    {
        if (session('reset_token') !== $token) {
            return redirect()->route('password.request')->withErrors([
                'identity' => 'Sesi reset password tidak valid atau telah kedaluwarsa.'
            ]);
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * Proses pembaruan kata sandi baru.
     */
    public function handleResetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (session('reset_token') !== $request->token) {
            return redirect()->route('password.request')->withErrors([
                'identity' => 'Sesi reset password tidak valid.'
            ]);
        }

        $nik = session('reset_nik');
        $role = session('reset_role');
        $newPassword = $request->password;

        if ($role === 'admin') {
            Admin::where('nik_admin', $nik)->update([
                'password' => Hash::make($newPassword)
            ]);
        } else {
            Pengguna::where('nik', $nik)->update([
                'password' => Hash::make($newPassword)
            ]);
        }

        // Hapus sesi reset
        session()->forget(['reset_token', 'reset_nik', 'reset_role']);

        return redirect()->route('login')->with('success', 'Kata sandi Anda berhasil diperbarui! Silakan masuk.');
    }
}
