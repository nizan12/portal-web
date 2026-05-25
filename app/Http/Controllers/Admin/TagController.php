<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function adminTags(Request $request)
    {
        $admin = auth('admin')->user();
        $search = trim((string) $request->query('q', ''));

        $tags = Tag::query()
            ->withCount('links')
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';
                $query->where('nama_tag', 'like', $keyword);
            })
            ->orderBy('nama_tag')
            ->get();

        $menuItems = $this->adminMenuItems('tags');
        $pageTitle = 'Kelola Tag - ' . config('app.name', 'POLTREE');
        $topbarTitle = 'Tag';

        $allLinks = Link::query()->whereNull('nik')->orderBy('nama_web')->get();

        return view('dashboard.admin.tags', compact('admin', 'tags', 'search', 'menuItems', 'pageTitle', 'topbarTitle', 'allLinks'));
    }

    public function storeTag(Request $request)
    {
        $request->validate([
            'nama_tag' => 'required|string|max:255|unique:t_tag,nama_tag',
            'link_ids' => 'nullable|array',
            'link_ids.*' => 'exists:t_link,id_link',
        ]);

        $tag = Tag::create([
            'nama_tag' => $request->nama_tag,
        ]);

        if ($request->has('link_ids')) {
            $tag->links()->sync($request->link_ids);
        }

        return back()->with('success', 'Tag berhasil ditambahkan.');
    }

    public function updateTag(Request $request, $id)
    {
        $request->validate([
            'nama_tag' => 'required|string|max:255|unique:t_tag,nama_tag,' . $id . ',id_tag',
            'link_ids' => 'nullable|array',
            'link_ids.*' => 'exists:t_link,id_link',
        ]);

        $tag = Tag::findOrFail($id);
        $tag->update([
            'nama_tag' => $request->nama_tag,
        ]);

        if ($request->has('link_ids')) {
            $tag->links()->sync($request->link_ids);
        } else {
            $tag->links()->detach();
        }

        return back()->with('success', 'Tag berhasil diperbarui.');
    }

    public function deleteTag($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return back()->with('success', 'Tag berhasil dihapus.');
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
