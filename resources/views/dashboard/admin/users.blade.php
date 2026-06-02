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
            <span class="users-subtitle">{{ $users->total() }} Pengguna Terdaftar</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="view-toggle-wrap">
                <button type="button" class="view-toggle-btn" data-view-mode="table" title="Tampilan Tabel">
                    <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3zM3 9h18M3 15h18M9 3v18"></path></svg>
                    <span>Tabel</span>
                </button>
                <button type="button" class="view-toggle-btn" data-view-mode="card" title="Tampilan Kartu">
                    <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"></rect><rect x="14" y="3" width="7" height="5" rx="1"></rect><rect x="14" y="12" width="7" height="9" rx="1"></rect><rect x="3" y="16" width="7" height="5" rx="1"></rect></svg>
                    <span>Kartu</span>
                </button>
            </div>
            <button type="button" class="btn-import" onclick="openImportModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Impor Pengguna
            </button>
            <button type="button" class="btn-add" onclick="openAddModal()">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1V13M1 7H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Tambah Pengguna
            </button>
        </div>
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
        <div class="view-wrapper view-mode-table">
            <div class="view-table-container">
                {{-- Tabel data pengguna --}}
                <div class="table-card mb-6 mt-10">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-16 pl-8">No</th>
                                <th class="w-[150px] text-center">NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Alamat Email</th>
                                <th>Role</th>
                                <th class="w-[120px] text-center pr-8">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td class="pl-8 text-[13px] text-gray-500 font-semibold">{{ $users->firstItem() + $index }}</td>
                                    <td class="text-center font-mono text-[13px] text-gray-600">{{ $user->nik }}</td>
                                    <td class="pl-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[12px] font-bold text-[#080d5f] overflow-hidden">
                                                @if($user->foto)
                                                    <img src="{{ asset($user->foto) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($user->nama_user, 0, 1)) }}
                                                @endif
                                            </div>
                                            <span class="font-bold text-[14px] text-[#080d5f]">{{ $user->nama_user }}</span>
                                        </div>
                                    </td>
                                    <td class="text-gray-500 text-[13px]">{{ $user->email }}</td>
                                    <td>
                                        <span class="role-badge {{ 
                                            $user->jabatan === 'Dosen' ? 'role-badge-dosen' : 
                                            ($user->jabatan === 'Tata Usaha' ? 'role-badge-tu' : 
                                            ($user->jabatan === 'Laboran' ? 'role-badge-laboran' : '')) 
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
            </div>
            <div class="view-card-container">
                {{-- Card View --}}
                <div class="users-grid mb-6">
                    @foreach ($users as $user)
                        <div class="user-card">
                            <div class="user-card-header">
                                <div class="user-card-avatar-wrap">
                                    @if($user->foto)
                                        <img src="{{ asset($user->foto) }}" alt="{{ $user->nama_user }}" class="user-card-avatar">
                                    @else
                                        <div class="user-card-avatar-placeholder">
                                            {{ strtoupper(substr($user->nama_user, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="user-card-meta">
                                    <h3 class="user-card-name" title="{{ $user->nama_user }}">{{ $user->nama_user }}</h3>
                                    <span class="user-card-nik">{{ $user->nik }}</span>
                                </div>
                            </div>
                            <div class="user-card-body">
                                <div class="user-card-info-item mb-2" title="{{ $user->email }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    <span class="truncate">{{ $user->email ?: '-' }}</span>
                                </div>
                                <div class="user-card-info-item">
                                    <span class="role-badge {{ 
                                        $user->jabatan === 'Dosen' ? 'role-badge-dosen' : 
                                        ($user->jabatan === 'Tata Usaha' ? 'role-badge-tu' : 
                                        ($user->jabatan === 'Laboran' ? 'role-badge-laboran' : '')) 
                                    }}">
                                        {{ $user->jabatan ?: 'User' }}
                                    </span>
                                </div>
                            </div>
                            <div class="user-card-actions">
                                <button type="button" class="btn-mini-action btn-mini-edit" title="Edit"
                                    onclick="openEditModal('{{ $user->nik }}', '{{ $user->nama_user }}', '{{ $user->email }}', '{{ $user->jabatan }}')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user->nik) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete(this, 'Apakah Anda yakin ingin menghapus pengguna &quot;{{ $user->nama_user }}&quot;?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-mini-action btn-mini-delete" title="Hapus">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mb-12">
            {{ $users->links('partials.pagination') }}
        </div>
    @else
        <div class="table-card p-12 text-center opacity-50 mt-10">
            Tidak ada pengguna ditemukan.
        </div>
    @endif



    @push('modals')
    {{-- MODAL: Tambah Pengguna --}}
    <div id="addModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell">
            <div class="premium-modal-card">
                <button type="button" onclick="closeAddModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <h2 class="premium-modal-title">Tambah Pengguna</h2>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">NIK</label>
                        <input type="text" name="nik" required class="premium-modal-input" placeholder="Masukkan NIK">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Nama</label>
                        <input type="text" name="nama_user" required class="premium-modal-input" placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Email</label>
                        <input type="email" name="email" required class="premium-modal-input" placeholder="Masukkan email">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Role</label>
                        <select name="jabatan" required class="premium-modal-input">
                            <option value="">Pilih Role</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Tata Usaha">Tata Usaha</option>
                            <option value="Laboran">Laboran</option>
                        </select>
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Kata Sandi</label>
                        <input type="password" name="password" required class="premium-modal-input" placeholder="Masukkan kata sandi">
                    </div>

                    <div class="premium-modal-actions">
                        <button type="button" onclick="closeAddModal()" class="premium-modal-btn btn-cancel">Batal</button>
                        <button type="submit" class="premium-modal-btn btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Edit Pengguna --}}
    <div id="editModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell">
            <div class="premium-modal-card">
                <button type="button" onclick="closeEditModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <h2 class="premium-modal-title">Edit Pengguna</h2>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">NIK</label>
                        <input type="text" id="edit_nik_display" disabled class="premium-modal-input opacity-60" style="background-color: #f8fafc;">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Nama</label>
                        <input type="text" name="nama_user" id="edit_nama" required class="premium-modal-input">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Email</label>
                        <input type="email" name="email" id="edit_email" required class="premium-modal-input">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Role</label>
                        <select name="jabatan" id="edit_jabatan" required class="premium-modal-input">
                            <option value="Dosen">Dosen</option>
                            <option value="Tata Usaha">Tata Usaha</option>
                            <option value="Laboran">Laboran</option>
                        </select>
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Kata Sandi (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="premium-modal-input" placeholder="Masukkan kata sandi baru">
                    </div>

                    <div class="premium-modal-actions">
                        <button type="button" onclick="closeEditModal()" class="premium-modal-btn btn-cancel">Batal</button>
                        <button type="submit" class="premium-modal-btn btn-save">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Impor Excel --}}
    <div id="importModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell">
            <div class="premium-modal-card">
                <button type="button" onclick="closeImportModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <h2 class="premium-modal-title">Impor Pengguna</h2>

                <div class="mb-5 p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-500 leading-relaxed" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 20px;">
                    <div style="font-weight: 700; color: #1e293b; margin-bottom: 6px;">Format Kolom Excel:</div>
                    <ul style="list-style-type: decimal; padding-left: 16px; margin: 0; display: flex; flex-direction: column; gap: 4px;">
                        <li><strong>NIK</strong> (wajib, unik/angka)</li>
                        <li><strong>Nama Lengkap</strong> (wajib)</li>
                        <li><strong>Alamat Email</strong> (wajib, format email unik)</li>
                        <li><strong>Role</strong> (wajib: Dosen, Tata Usaha, Laboran)</li>
                        <li><strong>Password</strong> (opsional, bawaan: <em>poltree123</em> jika kosong)</li>
                    </ul>
                    <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #e2e8f0; font-size: 11px;">
                        Anda dapat mengunduh format contoh Excel di sini: 
                        <a href="{{ route('admin.users.template') }}" style="color: #10b981; font-weight: 700; text-decoration: underline;">Unduh Template Excel</a>
                    </div>
                </div>

                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Pilih Berkas Excel (.xlsx, .xls, .csv)</label>
                        <div class="excel-upload-zone" id="excelDropzone">
                            <input type="file" name="excel_file" id="excelFileInput" required accept=".xlsx,.xls,.csv" style="display: none;">
                            <div class="excel-upload-prompt" onclick="document.getElementById('excelFileInput').click()">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 8px;">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <span class="upload-title" style="font-weight: 700; font-size: 13px; color: #1e293b;">Pilih file excel Anda</span>
                                <span class="upload-subtitle" style="font-size: 11px; color: #64748b;" id="excelFileName">atau seret file ke sini</span>
                            </div>
                        </div>
                    </div>

                    <div class="premium-modal-actions">
                        <button type="button" onclick="closeImportModal()" class="premium-modal-btn btn-cancel">Batal</button>
                        <button type="submit" class="premium-modal-btn btn-save" style="background: #10b981; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);">Mulai Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush
@endsection

@push('scripts')
<script>
    function openAddModal() {
        const select = document.querySelector('#addModal select[name="jabatan"]');
        if (select) {
            select.value = '';
            if (typeof select.refreshPremiumSelect === 'function') {
                select.refreshPremiumSelect();
            }
        }
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

    function openImportModal() {
        const m = document.getElementById('importModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeImportModal() {
        const m = document.getElementById('importModal');
        if (!m) return;
        m.classList.add('closing');
        setTimeout(() => {
            m.classList.add('hidden');
            m.classList.remove('flex', 'closing');
            document.getElementById('excelFileInput').value = '';
            document.getElementById('excelFileName').textContent = 'atau seret file ke sini';
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
        if (typeof document.getElementById('edit_jabatan').refreshPremiumSelect === 'function') {
            document.getElementById('edit_jabatan').refreshPremiumSelect();
        }
        
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
    window.addEventListener('click', function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        const importModal = document.getElementById('importModal');
        if (event.target === addModal) closeAddModal();
        if (event.target === editModal) closeEditModal();
        if (event.target === importModal) closeImportModal();
    });

    document.addEventListener('DOMContentLoaded', function() {
        initializeViewModeToggle('users');

        // Drag & Drop Setup
        const dropzone = document.getElementById('excelDropzone');
        const fileInput = document.getElementById('excelFileInput');
        const fileName = document.getElementById('excelFileName');

        if (dropzone && fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    fileName.textContent = file.name;
                } else {
                    fileName.textContent = 'atau seret file ke sini';
                }
            });

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, function() {
                    dropzone.style.borderColor = '#10b981';
                    dropzone.style.background = '#f0fdf4';
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function() {
                    dropzone.style.borderColor = '#cbd5e1';
                    dropzone.style.background = '#f8fafc';
                }, false);
            });

            // Handle dropped files
            dropzone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length) {
                    fileInput.files = files;
                    fileName.textContent = files[0].name;
                }
            }, false);
        }
    });
</script>
@endpush
