<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Link;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function admin()
    {
        $admin = auth('admin')->user();

        $stats = [
            [
                'label' => 'Jumlah Pengguna',
                'value' => $this->formatAdminStat(rescue(fn() => Pengguna::count(), 0, report: false)),
                'icon' => 'users',
            ],
            [
                'label' => 'Jumlah Layanan',
                'value' => $this->formatAdminStat(rescue(fn() => Link::count(), 0, report: false)),
                'icon' => 'link',
            ],
            [
                'label' => 'Jumlah Kategori',
                'value' => $this->formatAdminStat(rescue(fn() => Kategori::count(), 0, report: false)),
                'icon' => 'folder-user',
            ],
        ];

        $menuItems = $this->adminMenuItems('dashboard');
        $pageTitle = 'Dashboard Admin - ' . config('app.name', 'POLTREE');
        $topbarTitle = 'Dashboard';

        return view('dashboard.admin.index', compact('admin', 'stats', 'menuItems', 'pageTitle', 'topbarTitle'));
    }

    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = auth('admin')->user();

        if (!Hash::check($request->old_password, $admin->password)) {
            return back()->withErrors(['old_password' => 'Kata sandi lama tidak sesuai.']);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    private function formatAdminStat(int $value): string
    {
        return str_pad((string) $value, 2, '0', STR_PAD_LEFT);
    }

    private function adminMenuItems(string $active): array
    {
        return [
            ['label' => 'Dashboard', 'href' => route('admin.dashboard'), 'icon' => 'home', 'active' => $active === 'dashboard'],
            ['label' => 'Semua Layanan', 'href' => route('admin.services'), 'icon' => 'sparkles', 'active' => $active === 'services'],
            ['label' => 'Kelola Pengguna', 'href' => route('admin.users'), 'icon' => 'user', 'active' => $active === 'users'],
            ['label' => 'Kelola Layanan', 'href' => route('admin.links'), 'icon' => 'chain', 'active' => $active === 'links'],
            ['label' => 'Kelola Kategori', 'href' => route('admin.categories'), 'icon' => 'folder', 'active' => $active === 'categories'],
            ['label' => 'Kelola Tag', 'href' => route('admin.tags'), 'icon' => 'tag', 'active' => $active === 'tags'],
        ];
    }
}
