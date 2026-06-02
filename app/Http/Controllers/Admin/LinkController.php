<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function adminLinks(Request $request)
    {
        $admin = auth('admin')->user();
        $search = trim((string) $request->query('q', ''));

        $links = Link::query()
            ->with(['kategori', 'tags'])
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';
                $query->where('nama_web', 'like', $keyword)
                    ->orWhere('url', 'like', $keyword)
                    ->orWhere('deskripsi', 'like', $keyword);
            })
            ->orderBy('nama_web')
            ->paginate(10)
            ->withQueryString();

        $categories = Kategori::query()->whereNull('nik')->orderBy('nama_kategori')->get();
        $allTags = Tag::orderBy('nama_tag')->get();

        $menuItems = $this->adminMenuItems('links');
        $pageTitle = 'Kelola Layanan - ' . config('app.name', 'POLTREE');
        $topbarTitle = 'Layanan';

        return view('dashboard.admin.links', compact('admin', 'links', 'search', 'menuItems', 'pageTitle', 'topbarTitle', 'categories', 'allTags'));
    }

    public function storeLink(Request $request)
    {
        $url = $request->input('url');
        if ($url && !preg_match('/^(https?:\/\/)/i', $url)) {
            $url = 'https://' . $url;
            $request->merge(['url' => $url]);
        }

        $request->validate([
            'nama_web' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'nullable|exists:t_kategori,id_kategori',
            'role' => 'nullable|string',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:t_tag,id_tag',
            'status' => 'nullable|string',
        ]);

        $link = Link::create([
            'nama_web' => $request->nama_web,
            'url' => $request->url,
            'deskripsi' => $request->deskripsi,
            'id_kategori' => $request->id_kategori,
            'role' => $request->role,
            'status' => $request->status ?: 'aktif',
        ]);

        if ($request->has('tag_ids')) {
            $link->tags()->sync($request->tag_ids);
        }

        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function updateLink(Request $request, $id)
    {
        $url = $request->input('url');
        if ($url && !preg_match('/^(https?:\/\/)/i', $url)) {
            $url = 'https://' . $url;
            $request->merge(['url' => $url]);
        }

        $request->validate([
            'nama_web' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'nullable|exists:t_kategori,id_kategori',
            'role' => 'nullable|string',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:t_tag,id_tag',
            'status' => 'nullable|string',
        ]);

        $link = Link::findOrFail($id);
        $link->update([
            'nama_web' => $request->nama_web,
            'url' => $request->url,
            'deskripsi' => $request->deskripsi,
            'id_kategori' => $request->id_kategori,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        if ($request->has('tag_ids')) {
            $link->tags()->sync($request->tag_ids);
        } else {
            $link->tags()->detach();
        }

        return back()->with('success', 'Layanan berhasil diperbarui.');
    }

    public function deleteLink($id)
    {
        $link = Link::findOrFail($id);
        $link->delete();

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    public function checkAllLinks()
    {
        \Illuminate\Support\Facades\Artisan::call('links:check-status');

        return back()->with('success', 'Pemeriksaan status semua layanan telah dijalankan.');
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
            ['label' => 'Uji Test API', 'href' => route('admin.api-checker'), 'icon' => 'pulse', 'active' => $active === 'api-checker'],
        ];
    }
}
