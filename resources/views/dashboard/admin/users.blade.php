{{--
|--------------------------------------------------------------------------
| Admin – Kelola Pengguna
|--------------------------------------------------------------------------
| Tabel data pengguna: NIK, nama, role (jabatan).
| Fitur: pencarian, tombol edit/hapus (placeholder).
| Data: $users (Collection), $search (string).
|--------------------------------------------------------------------------
--}}
@extends('layouts.admin')

@section('content')
    {{-- Header: judul + tombol tambah --}}
    <div class="users-header">
        <div class="users-title-wrap">
            <h1 class="users-title">Kelola Pengguna</h1>
            <span class="users-subtitle">{{ $users->count() }} Pengguna Terdaftar</span>
        </div>
        <button type="button" class="btn-add" onclick="openAddModal()">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 1V13M1 7H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Tambah Pengguna
        </button>
    </div>

    {{-- Pencarian --}}
    <div class="search-container">
        <form action="{{ route('admin.users') }}" method="GET" class="search-input-wrap">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="q" value="{{ $search }}" class="search-input" placeholder="Cari pengguna...">
        </form>
    </div>

    @if ($users->isNotEmpty())
        {{-- Tabel data pengguna --}}
        <div class="table-card mb-12 mt-10">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-[150px] text-center">NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat Email</th>
                        <th>Role</th>
                        <th class="w-[120px] text-center pr-8">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-center font-mono text-[13px] text-gray-600">{{ $user->nik }}</td>
                            <td class="pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[12px] font-bold text-[#080d5f]">
                                        {{ strtoupper(substr($user->nama_user, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-[14px] text-[#080d5f]">{{ $user->nama_user }}</span>
                                </div>
                            </td>
                            <td class="text-gray-500 text-[13px]">{{ $user->email }}</td>
                            <td>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase {{ 
                                    $user->jabatan === 'Dosen' ? 'bg-indigo-100 text-indigo-700' : 
                                    ($user->jabatan === 'Tata Usaha' ? 'bg-amber-100 text-amber-700' : 
                                    ($user->jabatan === 'Laboran' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700')) 
                                }}">
                                    {{ $user->jabatan ?: 'User' }}
                                </span>
                            </td>
                            <td class="pr-8">
                                <div class="action-btns justify-center items-center">
                                    <button type="button" class="btn-action btn-edit shadow-sm" title="Edit"
                                        onclick="openEditModal('{{ $user->nik }}', '{{ $user->nama_user }}', '{{ $user->email }}', '{{ $user->jabatan }}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->nik) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete(this, 'Apakah Anda yakin ingin menghapus pengguna &quot;{{ $user->nama_user }}&quot;?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete shadow-sm" title="Hapus">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="table-card p-12 text-center opacity-50 mt-10">
            Tidak ada pengguna ditemukan.
        </div>
    @endif

    {{-- MODAL: Tambah Pengguna --}}
    <div id="addModal" class="password-modal hidden">
        <div class="password-modal-content">
            <h2 class="m-0 mb-5 text-xl font-bold text-[#080d5f] text-center">Tambah Pengguna</h2>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" required class="form-input" placeholder="Masukkan NIK">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama_user" required class="form-input" placeholder="Masukkan nama lengkap">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" required class="form-input" placeholder="Masukkan email">
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="jabatan" required class="form-input">
                        <option value="">Pilih Role</option>
                        <option value="Dosen">Dosen</option>
                        <option value="Tata Usaha">Tata Usaha</option>
                        <option value="Laboran">Laboran</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="password" required class="form-input" placeholder="Masukkan kata sandi">
                </div>
                <div class="flex gap-2.5 mt-8">
                    <button type="button" onclick="closeAddModal()" class="flex-1 h-12 rounded-xl border border-gray-200 bg-white cursor-pointer font-semibold text-gray-500">Batal</button>
                    <button type="submit" class="flex-1 h-12 rounded-xl border-0 bg-[#080d5f] text-white cursor-pointer font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Edit Pengguna --}}
    <div id="editModal" class="password-modal hidden">
        <div class="password-modal-content">
            <h2 class="m-0 mb-5 text-xl font-bold text-[#080d5f] text-center">Edit Pengguna</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">NIK</label>
                    <input type="text" id="edit_nik_display" disabled class="form-input bg-gray-50 opacity-70">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama_user" id="edit_nama" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="jabatan" id="edit_jabatan" required class="form-input">
                        <option value="Dosen">Dosen</option>
                        <option value="Tata Usaha">Tata Usaha</option>
                        <option value="Laboran">Laboran</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan kata sandi baru">
                </div>
                <div class="flex gap-2.5 mt-8">
                    <button type="button" onclick="closeEditModal()" class="flex-1 h-12 rounded-xl border border-gray-200 bg-white cursor-pointer font-semibold text-gray-500">Batal</button>
                    <button type="submit" class="flex-1 h-12 rounded-xl border-0 bg-[#080d5f] text-white cursor-pointer font-semibold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openAddModal() {
        const m = document.getElementById('addModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeAddModal() {
        const m = document.getElementById('addModal');
        if (!m) return;
        m.classList.add('closing');
        setTimeout(() => {
            m.classList.add('hidden');
            m.classList.remove('flex', 'closing');
        }, 300);
    }

    function openEditModal(nik, nama, email, jabatan) {
        const m = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        // Set action URL
        form.action = `/admin/users/${nik}`;
        
        // Fill data
        document.getElementById('edit_nik_display').value = nik;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_jabatan').value = jabatan;
        
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeEditModal() {
        const m = document.getElementById('editModal');
        if (!m) return;
        m.classList.add('closing');
        setTimeout(() => {
            m.classList.add('hidden');
            m.classList.remove('flex', 'closing');
        }, 300);
    }

    // Close on outside click
    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        if (event.target == addModal) closeAddModal();
        if (event.target == editModal) closeEditModal();
    }
</script>
@endpush
