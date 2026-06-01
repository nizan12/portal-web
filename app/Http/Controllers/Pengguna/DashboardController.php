<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Jobs\CheckLinkStatus;
use App\Models\Kategori;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function pengguna(Request $request)
    {
        $roles = ['Dosen', 'Tata Usaha', 'Laboran'];
        $activeRole = $request->query('role');
        if (!in_array($activeRole, $roles, true)) {
            $activeRole = null;
        }
        $search = trim((string) $request->query('q', ''));

        $userNik = auth('pengguna')->user()->nik;

        $roleHasMatches = $activeRole ? Link::query()
            ->where(function ($q) use ($userNik) {
                $q->whereNull('nik')->orWhere('nik', $userNik);
            })
            ->where(function ($q) use ($activeRole) {
                $q->where('role', 'like', '%' . $activeRole . '%')
                    ->orWhereHas('tags', function ($t) use ($activeRole) {
                        $t->where('nama_tag', 'like', '%' . $activeRole . '%');
                    });
            })
            ->exists() : false;

        $services = Link::query()
            ->with('kategori')
            ->where('status', 'aktif')
            ->where(function ($q) use ($userNik) {
                $q->whereNull('nik')->orWhere('nik', $userNik);
            })
            ->when($activeRole && $roleHasMatches, function ($query) use ($activeRole) {
                $query->where(function ($q) use ($activeRole) {
                    $q->where('role', 'like', '%' . $activeRole . '%')
                        ->orWhereHas('kategori', function ($kategori) use ($activeRole) {
                            $kategori->where('nama_kategori', 'like', '%' . $activeRole . '%');
                        });
                });
            })
            ->when($search !== '', function ($items) use ($search) {
                $items->where(function ($query) use ($search) {
                    $keyword = '%' . $search . '%';

                    $query->where('nama_web', 'like', $keyword)
                        ->orWhere('url', 'like', $keyword)
                        ->orWhere('deskripsi', 'like', $keyword)
                        ->orWhereHas('kategori', function ($kategori) use ($keyword) {
                            $kategori->where('nama_kategori', 'like', $keyword);
                        })
                        ->orWhereHas('tags', function ($tags) use ($keyword) {
                            $tags->where('nama_tag', 'like', $keyword);
                        })
                        ->orWhere('status', 'like', $keyword);
                });
            })
            ->with(['kategori', 'tags'])
            ->orderByDesc('hit_point')
            ->orderBy('nama_web')
            ->get()
            ->map(function (Link $link) {
                $resolvedStatus = $link->resolved_status;

                return [
                    'title' => $link->nama_web,
                    'url' => $link->normalized_url ?: '#',
                    'description' => $link->deskripsi ?: 'Layanan website Politeknik Negeri Batam yang tersedia di portal POLTREE.',
                    'category' => $link->kategori?->nama_kategori ?: '',
                    'status' => $resolvedStatus,
                    'status_link' => $link->status_link ?: 'belum dicek',
                    'is_online' => $link->status_link !== 'bermasalah',
                    'is_custom' => $link->nik !== null,
                    'id' => $link->id_link,
                    'role' => $link->role,
                    'tags' => $link->tags->pluck('nama_tag')->toArray(),
                    'tag_ids' => $link->tags->pluck('id_tag')->toArray(),
                ];
            });

        $partitioned = $services->partition(function ($service) {
            return !$service['is_custom'];
        });

        $adminServices = $partitioned[0];
        $userServices = $partitioned[1];

        $heroImages = collect(glob(public_path('campus*.{png,jpg,jpeg,webp}'), GLOB_BRACE) ?: [])
            ->sortBy(fn($path) => basename($path), SORT_NATURAL)
            ->map(fn($path) => asset(basename($path)))
            ->values();

        if ($heroImages->isEmpty()) {
            $heroImages = collect([asset('campus.png')]);
        }

        $categoriesList = Kategori::query()
            ->with(['links' => function($q) use ($userNik) {
                $q->where(function ($query) use ($userNik) {
                    $query->whereNull('nik')->orWhere('nik', $userNik);
                });
            }])
            ->where(function ($query) {
                $query->whereNull('nik')
                    ->orWhere('nik', auth('pengguna')->user()->nik);
            })
            ->orderBy('nama_kategori')
            ->get();

        $categories = $categoriesList->pluck('nama_kategori')->filter()->unique()->values();

        $allLinkTitles = Link::query()
            ->where(function ($q) use ($userNik) {
                $q->whereNull('nik')->orWhere('nik', $userNik);
            })
            ->orderBy('nama_web')
            ->pluck('nama_web')
            ->unique()
            ->values();

        if ($categories->isEmpty()) {
            $categories = $services
                ->pluck('category')
                ->filter()
                ->unique()
                ->values();
        }

        $allAdminTags = Tag::orderBy('nama_tag')->get();

        return view('dashboard.pengguna.index', compact('services', 'adminServices', 'userServices', 'roles', 'activeRole', 'search', 'heroImages', 'categories', 'allLinkTitles', 'categoriesList', 'allAdminTags'));
    }

    public function storeUserLink(Request $request)
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
            'role' => 'nullable|string|in:Dosen,Tata Usaha,Laboran',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:t_tag,id_tag',
        ]);

        $link = Link::create([
            'nama_web' => $request->nama_web,
            'url' => $request->url,
            'deskripsi' => $request->deskripsi,
            'role' => $request->role,
            'nik' => auth('pengguna')->user()->nik,
            'status' => 'aktif',
        ]);

        if ($request->has('tag_ids')) {
            $link->tags()->sync($request->tag_ids);
        }

        // Jalankan health check di background agar tidak blocking
        CheckLinkStatus::dispatch($link->id_link);

        return back()->with('success', 'Link kustom berhasil ditambahkan.');
    }

    public function updateUserLink(Request $request, $id)
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
            'role' => 'nullable|string|in:Dosen,Tata Usaha,Laboran',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:t_tag,id_tag',
        ]);

        $link = Link::where('id_link', $id)
            ->where('nik', auth('pengguna')->user()->nik)
            ->firstOrFail();

        $oldUrl = $link->url;

        $link->update([
            'nama_web' => $request->nama_web,
            'url' => $request->url,
            'deskripsi' => $request->deskripsi,
            'role' => $request->role,
        ]);

        if ($request->has('tag_ids')) {
            $link->tags()->sync($request->tag_ids);
        } else {
            $link->tags()->detach();
        }

        // Jalankan health check di background jika URL berubah
        if ($oldUrl !== $request->url) {
            CheckLinkStatus::dispatch($link->id_link);
        }

        return back()->with('success', 'Link kustom berhasil diperbarui.');
    }

    public function deleteUserLink($id)
    {
        $link = Link::where('id_link', $id)
            ->where('nik', auth('pengguna')->user()->nik)
            ->firstOrFail();

        $link->delete();

        return back()->with('success', 'Link kustom berhasil dihapus.');
    }

    public function updatePenggunaPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth('pengguna')->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Kata sandi lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth('pengguna')->user();

        $request->validate([
            'nama_user' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'nama_user' => $request->nama_user,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path($user->foto))) {
                @unlink(public_path($user->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_photos'), $filename);
            
            $data['foto'] = 'uploads/profile_photos/' . $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
