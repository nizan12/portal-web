<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Link;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $admin = auth('admin')->user();

        // ── A. Memanggil Stored Procedure & Aggregates ──────────────────────
        // Menjalankan Stored Procedure untuk mengambil data statistik dashboard
        DB::statement("CALL sp_get_dashboard_statistics(@total_links, @active_links, @avg_response_time, @most_active_category)");
        $procResults = DB::select("SELECT @total_links AS total_links, @active_links AS active_links, @avg_response_time AS avg_response_time, @most_active_category AS most_active_category")[0];

        $stats = [
            [
                'label' => 'Jumlah Pengguna',
                'value' => $this->formatAdminStat(rescue(fn() => Pengguna::count(), 0, report: false)),
                'icon' => 'users',
            ],
            [
                'label' => 'Jumlah Layanan',
                'value' => $this->formatAdminStat($procResults->total_links ?? 0),
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
        $totalLayanan = $procResults->total_links ?? 0;
        $totalKategori = Kategori::count();

        // 1. Publik vs Pribadi
        $layananPublik = Link::whereNull('nik')->count();
        $layananPribadi = Link::whereNotNull('nik')->count();

        // 2. Health Status Check
        $layananAman = $procResults->active_links ?? 0;
        $layananDowntime = Link::whereNotNull('status_http_code')->where('status_http_code', '!=', 200)->count();
        $layananBelumDicek = Link::whereNull('status_http_code')->count();

        // 3. Waktu Respon Rata-rata dari Stored Procedure
        $avgResponseTime = $procResults->avg_response_time ?? 0;

        // 4. Layanan Terpopuler (Top Clicked Links)
        $topLinks = Link::orderBy('hit_point', 'desc')->take(5)->get();

        // ── B. Memanggil Stored Function & Subquery Lanjutan ────────────────
        // Menggunakan Stored Function 'sf_get_category_link_count' untuk menghitung tautan per kategori
        $topCategories = Kategori::select('*')
            ->selectRaw('sf_get_category_link_count(id_kategori) AS links_count')
            ->orderBy('links_count', 'desc')
            ->take(5)
            ->get();

        $statsDetail = compact(
            'totalPengguna', 'totalLayanan', 'totalKategori',
            'layananPublik', 'layananPribadi',
            'layananAman', 'layananDowntime', 'layananBelumDicek',
            'avgResponseTime', 'topLinks', 'topCategories'
        );

        // Tambahkan informasi kategori teraktif dari Stored Procedure ke statsDetail
        $statsDetail['mostActiveCategoryFromProc'] = $procResults->most_active_category ?? 'Tidak Ada';

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
