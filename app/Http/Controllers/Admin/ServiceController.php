<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Link;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function adminServices(Request $request)
    {
        $admin = auth('admin')->user();
        $search = trim((string) $request->query('q', ''));
        $selectedCategory = trim((string) $request->query('category', ''));

        $services = Link::query()
            ->with('kategori')
            ->where('status', 'aktif')
            ->when($search !== '', function ($items) use ($search) {
                $items->where(function ($query) use ($search) {
                    $keyword = '%' . $search . '%';

                    $query->where('nama_web', 'like', $keyword)
                        ->orWhere('url', 'like', $keyword)
                        ->orWhere('deskripsi', 'like', $keyword)
                        ->orWhereHas('kategori', function ($kategori) use ($keyword) {
                            $kategori->where('nama_kategori', 'like', $keyword);
                        })
                        ->orWhere('status', 'like', $keyword);
                });
            })
            ->when($selectedCategory !== '', function ($items) use ($selectedCategory) {
                $items->whereHas('kategori', function ($kategori) use ($selectedCategory) {
                    $kategori->where('nama_kategori', $selectedCategory);
                });
            })
            ->orderByDesc('hit_point')
            ->orderBy('nama_web')
            ->get()
            ->map(function (Link $link) {
                $resolvedStatus = $link->resolved_status;
                $category = $link->kategori?->nama_kategori ?: '';

                return [
                    'title' => $link->nama_web ?: 'Politeknik Negeri Batam',
                    'url' => $link->normalized_url ?: '#',
                    'description' => $link->deskripsi ?: 'Website Politeknik Negeri Batam (polibatam.ac.id) adalah website resmi kampus yang berfungsi sebagai pusat informasi dan layanan digital untuk mahasiswa, calon mahasiswa, dosen, dan masyarakat umum.',
                    'category' => $category,
                    'status' => $resolvedStatus,
                    'status_link' => $link->status_link ?: 'belum dicek',
                    'is_online' => $link->status_link !== 'bermasalah',
                ];
            });

        $categories = Kategori::query()
            ->has('links')
            ->orderBy('nama_kategori')
            ->pluck('nama_kategori')
            ->filter()
            ->unique()
            ->values();

        $menuItems = $this->adminMenuItems('services');
        $pageTitle = 'Semua Layanan - ' . config('app.name', 'POLTREE');
        $topbarTitle = 'Dashboard';

        return view('dashboard.admin.services', compact(
            'admin',
            'services',
            'categories',
            'selectedCategory',
            'search',
            'menuItems',
            'pageTitle',
            'topbarTitle'
        ));
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
