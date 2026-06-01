<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function adminUsers(Request $request)
    {
        $admin = auth('admin')->user();
        $search = trim((string) $request->query('q', ''));

        $users = Pengguna::query()
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';
                $query->where('nik', 'like', $keyword)
                    ->orWhere('nama_user', 'like', $keyword)
                    ->orWhere('email', 'like', $keyword)
                    ->orWhere('jabatan', 'like', $keyword);
            })
            ->orderBy('nama_user')
            ->paginate(10)
            ->withQueryString();

        $menuItems = $this->adminMenuItems('users');
        $pageTitle = 'Kelola Pengguna - ' . config('app.name', 'POLTREE');
        $topbarTitle = 'Pengguna';

        return view('dashboard.admin.users', compact('admin', 'users', 'search', 'menuItems', 'pageTitle', 'topbarTitle'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|unique:t_pengguna,nik',
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:t_pengguna,email',
            'jabatan' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        Pengguna::create([
            'nik' => $request->nik,
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $nik)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:t_pengguna,email,' . $nik . ',nik',
            'jabatan' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $user = Pengguna::findOrFail($nik);
        $data = [
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function deleteUser($nik)
    {
        $user = Pengguna::findOrFail($nik);
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
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
