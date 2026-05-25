<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Proses login — cek t_admin dulu, lalu t_pengguna (plaintext password).
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
        $admin = Admin::where('nik_admin', $nip)->first();

        if ($admin && $admin->password === $password) {
            Auth::guard('admin')->login($admin, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // ── 2. Coba login sebagai Pengguna (t_pengguna) ────────────────────
        $pengguna = Pengguna::where('nik', $nip)
            ->orWhere('email', $nip)
            ->first();

        if ($pengguna && $pengguna->password === $password) {
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
}
