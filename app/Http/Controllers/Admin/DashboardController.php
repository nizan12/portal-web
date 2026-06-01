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

        // Statistik Tambahan Lengkap
        $totalPengguna = Pengguna::count();
        $totalLayanan = Link::count();
        $totalKategori = Kategori::count();

        // 1. Publik vs Pribadi
        $layananPublik = Link::whereNull('nik')->count();
        $layananPribadi = Link::whereNotNull('nik')->count();

        // 2. Health Status Check
        $layananAman = Link::where('status_http_code', 200)->count();
        $layananDowntime = Link::whereNotNull('status_http_code')->where('status_http_code', '!=', 200)->count();
        $layananBelumDicek = Link::whereNull('status_http_code')->count();

        // 3. Waktu Respon Rata-rata
        $avgResponseTime = round(Link::whereNotNull('status_response_time_ms')->avg('status_response_time_ms') ?? 0);

        // 4. Layanan Terpopuler (Top Clicked Links)
        $topLinks = Link::orderBy('hit_point', 'desc')->take(5)->get();

        // 5. Kategori Terpopuler
        $topCategories = Kategori::withCount('links')->orderBy('links_count', 'desc')->take(5)->get();

        $statsDetail = compact(
            'totalPengguna', 'totalLayanan', 'totalKategori',
            'layananPublik', 'layananPribadi',
            'layananAman', 'layananDowntime', 'layananBelumDicek',
            'avgResponseTime', 'topLinks', 'topCategories'
        );

        $menuItems = $this->adminMenuItems('dashboard');
        $pageTitle = 'Dashboard Admin - ' . config('app.name', 'POLTREE');
        $topbarTitle = 'Dashboard';

        return view('dashboard.admin.index', compact('admin', 'stats', 'statsDetail', 'menuItems', 'pageTitle', 'topbarTitle'));
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

    public function updateProfile(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($admin->foto && file_exists(public_path($admin->foto))) {
                @unlink(public_path($admin->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_photos'), $filename);
            
            $data['foto'] = 'uploads/profile_photos/' . $filename;
        }

        $admin->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
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
