<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Link;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function adminCategories(Request $request)
    {
        $admin = auth('admin')->user();
        $search = trim((string) $request->query('q', ''));

        $categories = Kategori::query()
            ->withCount('links')
            ->whereNull('nik') // Only global categories for admin management
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';
                $query->where('nama_kategori', 'like', $keyword);
            })
            ->orderBy('nama_kategori')
            ->get();

        $menuItems = $this->adminMenuItems('categories');
        $pageTitle = 'Kelola Kategori - ' . config('app.name', 'POLTREE');
        $topbarTitle = 'Kategori';

        $allLinks = Link::query()->whereNull('nik')->orderBy('nama_web')->get();

        return view('dashboard.admin.categories', compact('admin', 'categories', 'search', 'menuItems', 'pageTitle', 'topbarTitle', 'allLinks'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'link_ids' => 'nullable|array',
            'link_ids.*' => 'exists:t_link,id_link',
        ]);

        $cat = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'nik' => null, // Global category
        ]);

        if ($request->has('link_ids')) {
            Link::whereIn('id_link', $request->link_ids)->update(['id_kategori' => $cat->id_kategori]);
        }

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'link_ids' => 'nullable|array',
            'link_ids.*' => 'exists:t_link,id_link',
        ]);

        $category = Kategori::findOrFail($id);
        $category->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        // Update links association
        Link::where('id_kategori', $id)->update(['id_kategori' => null]);

        if ($request->has('link_ids')) {
            Link::whereIn('id_link', $request->link_ids)->update(['id_kategori' => $id]);
        }

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function deleteCategory($id)
    {
        $category = Kategori::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
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
