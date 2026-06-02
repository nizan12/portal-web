{{--
|--------------------------------------------------------------------------
| Admin – Kelola Layanan (Links)
|--------------------------------------------------------------------------
| CRUD layanan/link: tabel data, pencarian, modal tambah/edit.
| Data: $links (Collection), $search (string).
|--------------------------------------------------------------------------
--}}
@extends('layouts.admin')

@section('content')
    {{-- Header: judul + tombol tambah --}}
    <div class="links-header">
        <div class="links-title-wrap">
            <h1 class="links-title">Kelola Layanan</h1>
            <span class="links-subtitle">{{ $links->total() }} Layanan Terdaftar</span>
        </div>
        <div class="flex gap-3 items-center">
            <div class="view-toggle-wrap">
                <button type="button" class="view-toggle-btn active" data-view-mode="table" title="Tampilan Tabel">
                    <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3zM3 9h18M3 15h18M9 3v18"></path></svg>
                    <span>Tabel</span>
                </button>
                <button type="button" class="view-toggle-btn" data-view-mode="card" title="Tampilan Kartu">
                    <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"></rect><rect x="14" y="3" width="7" height="5" rx="1"></rect><rect x="14" y="12" width="7" height="9" rx="1"></rect><rect x="3" y="16" width="7" height="5" rx="1"></rect></svg>
                    <span>Kartu</span>
                </button>
            </div>
            <form action="{{ route('admin.links.check') }}" method="POST">
                @csrf
                <button type="submit" class="btn-check-status">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 4v6h-6"></path>
                        <path d="M1 20v-6h6"></path>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                    Cek Status
                </button>
            </form>
            <button type="button" class="btn-add" onclick="addLink()">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1V13M1 7H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Tambah Layanan
            </button>
        </div>
    </div>

    {{-- Pencarian --}}
    <div class="search-container">
        <form action="{{ route('admin.links') }}" method="GET" class="search-input-wrap">
            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="q" value="{{ $search }}" class="search-input" placeholder="Cari nama layanan atau URL...">
        </form>
    </div>

    @if ($links->isNotEmpty())
        <div class="view-wrapper view-mode-table">
            <div class="view-table-container">
                <div class="table-card mt-8 mb-6">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-16 pl-8">No</th>
                                <th>Layanan</th>
                                <th>Status</th>
                                <th>Status Link</th>
                                <th>Kategori</th>
                                <th>Tag</th>
                                <th>Terakhir Dicek</th>
                                <th class="w-[120px] text-center pr-8">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($links as $index => $link)
                                <tr>
                                    <td class="pl-8">{{ $links->firstItem() + $index }}</td>
                                    <td>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-[14px] text-[#080d5f] mb-1">{{ $link->nama_web }}</span>
                                            <a href="{{ $link->normalized_url }}" target="_blank" class="text-[10px] text-blue-400 flex items-center gap-1 group">
                                                {{ Str::limit(str_replace(['http://', 'https://'], '', $link->url), 30) }}
                                                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @php $statusClass = $link->resolved_status === 'aktif' ? 'status-active' : 'status-inactive'; @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $link->resolved_status === 'aktif' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.4)]' }}"></span>
                                            {{ ucfirst($link->resolved_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Status Link (API Check) --}}
                                        @if(!$link->status_checked_at)
                                            <span class="status-badge bg-gray-50 text-gray-400 border border-gray-200/50 flex items-center w-max px-2.5 py-1 rounded-full text-[10px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                                Belum Dicek
                                            </span>
                                        @elseif($link->status_link === 'aktif')
                                            <span class="status-badge bg-emerald-50 text-emerald-700 border border-emerald-100/60 flex items-center w-max px-2.5 py-1 rounded-full text-[10px] font-bold" title="{{ $link->status_summary }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)] animate-pulse"></span>
                                                Online
                                            </span>
                                        @elseif($link->status_link === 'bermasalah')
                                            <span class="status-badge bg-rose-50 text-rose-700 border border-rose-100/60 flex items-center w-max px-2.5 py-1 rounded-full text-[10px] font-bold" title="{{ $link->status_summary }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]"></span>
                                                Offline
                                            </span>
                                        @else
                                            <span class="status-badge bg-gray-50 text-gray-500 border border-gray-200/50 flex items-center w-max px-2.5 py-1 rounded-full text-[10px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                {{ ucfirst($link->status_link) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td><span class="badge-kategori">{{ $link->kategori?->nama_kategori ?: 'Umum' }}</span></td>
                                    <td>
                                        <div class="flex flex-wrap gap-1.5 max-w-[150px]">
                                            @forelse($link->tags as $tag)
                                                <span class="badge-tag">{{ $tag->nama_tag }}</span>
                                            @empty
                                                <span class="text-gray-300 text-[10px] italic">No Tags</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="text-[11px] text-gray-500">
                                        <div class="flex flex-col">
                                            <span>{{ $link->status_checked_at ? $link->status_checked_at->diffForHumans() : '-' }}</span>
                                            @if($link->status_checked_at)
                                                <span class="text-[9px] opacity-60">{{ $link->status_checked_at->format('d M Y, H:i') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="pr-8">
                                        <div class="action-btns justify-center items-center">
                                            <button type="button" class="btn-action btn-edit" onclick="editLink({{ json_encode([
                                                'id' => $link->id_link,
                                                'nama_web' => $link->nama_web,
                                                'url' => $link->url,
                                                'deskripsi' => $link->deskripsi,
                                                'id_kategori' => $link->id_kategori,
                                                'tag_ids' => $link->tags->pluck('id_tag')->toArray(),
                                                'status' => $link->status
                                            ]) }})">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.links.destroy', $link->id_link) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete(this, 'Apakah Anda yakin ingin menghapus layanan &quot;{{ $link->nama_web }}&quot;?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
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
                <div class="links-grid mb-6">
                    @foreach ($links as $link)
                        <div class="link-card">
                            <div class="link-card-header">
                                <div class="link-card-icon-box">
                                    <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo" style="width: 24px; height: 24px; object-fit: contain;">
                                </div>
                                <div class="link-card-title-wrap">
                                    <h3 class="link-card-title" title="{{ $link->nama_web }}">{{ $link->nama_web }}</h3>
                                    <a href="{{ $link->normalized_url }}" target="_blank" class="link-card-url" title="{{ $link->url }}">{{ $link->url }}</a>
                                </div>
                            </div>
                            <p class="link-card-desc">{{ $link->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                            <div class="link-card-meta">
                                <span class="category-badge">{{ $link->kategori?->nama_kategori ?: 'Umum' }}</span>
                                @if($link->tags->isNotEmpty())
                                    <div class="link-card-tags">
                                        @foreach($link->tags as $t)
                                            <span class="tag-badge">{{ $t->nama_tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="link-card-footer">
                                <div class="link-card-status">
                                    @php
                                        $isOnline = $link->status_link === 'aktif';
                                        $isBermasalah = $link->status_link === 'bermasalah';
                                    @endphp
                                    <span class="status-dot {{ !$link->status_checked_at ? 'bg-gray-300' : ($isOnline ? '' : ($isBermasalah ? 'offline' : 'bg-gray-400')) }}"></span>
                                    <span class="status-text {{ !$link->status_checked_at ? 'text-gray-400' : ($isOnline ? 'text-green-600' : ($isBermasalah ? 'text-red-600' : 'text-gray-500')) }}">
                                        @if(!$link->status_checked_at)
                                            Belum dicek
                                        @elseif($isOnline)
                                            Online
                                        @elseif($isBermasalah)
                                            Offline
                                        @else
                                            {{ ucfirst($link->status_link) }}
                                        @endif
                                    </span>
                                </div>
                                <div class="link-card-actions">
                                    <button type="button" class="btn-mini-action btn-mini-edit" title="Edit" onclick="editLink({{ json_encode([
                                        'id' => $link->id_link,
                                        'nama_web' => $link->nama_web,
                                        'url' => $link->url,
                                        'deskripsi' => $link->deskripsi,
                                        'id_kategori' => $link->id_kategori,
                                        'tag_ids' => $link->tags->pluck('id_tag')->toArray(),
                                        'status' => $link->status
                                    ]) }})">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.links.destroy', $link->id_link) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete(this, 'Apakah Anda yakin ingin menghapus layanan &quot;{{ $link->nama_web }}&quot;?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-mini-action btn-mini-delete" title="Hapus"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mb-12">
            {{ $links->links('partials.pagination') }}
        </div>
    @else
        <div class="table-card p-12 text-center opacity-50 mt-8">Tidak ada layanan ditemukan.</div>
    @endif



    @push('modals')
    {{-- ═══════════════════════════════════════════════════════
         MODAL: Tambah/Edit Layanan
         ═══════════════════════════════════════════════════════ --}}
    <div id="linkModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell" id="modalContent">
            <div class="premium-modal-card">
                <button type="button" onclick="closeModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <h2 id="modalTitle" class="premium-modal-title">Tambah Layanan</h2>
                <form id="linkForm" action="{{ route('admin.links.store') }}" method="POST">
                    @csrf
                    <div id="methodField"></div>

                    {{-- Form scrollable wrapper to keep modal compact like confirm delete modal --}}
                    <div class="premium-modal-scroll-container" style="max-height: 300px; overflow-y: auto; padding-right: 12px; margin-bottom: 20px;">
                        <div class="premium-modal-form-group">
                            <label class="premium-modal-label">Nama Layanan</label>
                            <input type="text" name="nama_web" id="nama_web" placeholder="Contoh: SIAKAD" required class="premium-modal-input">
                        </div>

                        <div class="premium-modal-form-group">
                            <label class="premium-modal-label">URL Layanan</label>
                            <input type="text" name="url" id="url" placeholder="https://example.com" required class="premium-modal-input">
                        </div>

                        <div class="premium-modal-form-group">
                            <label class="premium-modal-label">Deskripsi Singkat</label>
                            <textarea name="deskripsi" id="deskripsi" placeholder="Jelaskan fungsi layanan ini..." class="premium-modal-textarea"></textarea>
                        </div>

                        <div class="premium-modal-form-group">
                            <div class="flex justify-between items-center mb-2">
                                <label class="premium-modal-label" style="margin-bottom: 0;">Kategori</label>
                                <button type="button" onclick="toggleQuickCategoryForm()" class="cb-add-link-btn" style="background: none; border: none; padding: 0; color: var(--orange);">
                                    + Tambah Kategori Baru
                                </button>
                            </div>
                            <select name="id_kategori" id="id_kategori" class="premium-modal-input appearance-none cursor-pointer">
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>

                            {{-- Quick Add Category Section (Initially Hidden) --}}
                            <div id="quickCategoryContainer" class="hidden" style="display: none; margin-top: 12px; padding: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; flex-direction: column; gap: 8px;">
                                <span style="font-size: 11.5px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Tambah Kategori Baru</span>
                                <div style="display: flex; gap: 8px; align-items: center; width: 100%;">
                                    <input type="text" id="quick_nama_kategori" placeholder="Nama Kategori..." class="premium-modal-input" style="height: 38px; min-height: 0; padding: 0 12px; font-size: 13px; flex: 1; border-radius: 8px; background: #ffffff;">
                                    <button type="button" onclick="submitQuickCategory()" style="height: 38px; padding: 0 16px; border-radius: 8px; background: var(--orange, #f97316); color: white; font-size: 12px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.opacity=0.9" onmouseout="this.style.opacity=1">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="premium-modal-form-group">
                            <label class="premium-modal-label">Status Layanan</label>
                            <select name="status" id="status" class="premium-modal-input appearance-none cursor-pointer">
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak Aktif</option>
                                <option value="bermasalah">Bermasalah</option>
                            </select>
                        </div>

                        <div class="premium-modal-form-group">
                            <label class="premium-modal-label">Tag Layanan</label>
                            <div class="premium-modal-tags-wrapper">
                                @foreach($allTags as $tag)
                                    <label class="premium-modal-tag-pill">
                                        <input type="checkbox" name="tag_ids[]" value="{{ $tag->id_tag }}">
                                        <span>{{ $tag->nama_tag }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="premium-modal-actions">
                        <button type="button" onclick="closeModal()" class="premium-modal-btn btn-cancel">Batal</button>
                        <button type="submit" class="premium-modal-btn btn-save">Simpan Layanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush

    {{-- ═══════════════════════════════════════════════════════
         SCRIPT: Logika modal layanan
         ═══════════════════════════════════════════════════════ --}}
    <script>
        function addLink() {
            document.getElementById('modalTitle').innerText = 'Tambah Layanan';
            document.getElementById('linkForm').action = "{{ route('admin.links.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('nama_web').value = '';
            document.getElementById('url').value = '';
            document.getElementById('deskripsi').value = '';
            document.getElementById('id_kategori').value = '';
            document.getElementById('status').value = 'aktif';
            
            if (typeof document.getElementById('id_kategori').refreshPremiumSelect === 'function') {
                document.getElementById('id_kategori').refreshPremiumSelect();
            }
            if (typeof document.getElementById('status').refreshPremiumSelect === 'function') {
                document.getElementById('status').refreshPremiumSelect();
            }
            
            // Reset quick category form
            const quickContainer = document.getElementById('quickCategoryContainer');
            if (quickContainer) {
                quickContainer.style.display = 'none';
            }
            const quickInput = document.getElementById('quick_nama_kategori');
            if (quickInput) {
                quickInput.value = '';
            }

            // Reset tags
            document.querySelectorAll('input[name="tag_ids[]"]').forEach(cb => cb.checked = false);
            
            showModal();
        }

        function editLink(data) {
            document.getElementById('modalTitle').innerText = 'Edit Layanan';
            document.getElementById('linkForm').action = "/admin/links/" + data.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('nama_web').value = data.nama_web || '';
            document.getElementById('url').value = data.url || '';
            document.getElementById('deskripsi').value = data.deskripsi || '';
            document.getElementById('id_kategori').value = data.id_kategori || '';
            document.getElementById('status').value = data.status || 'aktif';
            
            if (typeof document.getElementById('id_kategori').refreshPremiumSelect === 'function') {
                document.getElementById('id_kategori').refreshPremiumSelect();
            }
            if (typeof document.getElementById('status').refreshPremiumSelect === 'function') {
                document.getElementById('status').refreshPremiumSelect();
            }
            
            // Reset quick category form
            const quickContainer = document.getElementById('quickCategoryContainer');
            if (quickContainer) {
                quickContainer.style.display = 'none';
            }
            const quickInput = document.getElementById('quick_nama_kategori');
            if (quickInput) {
                quickInput.value = '';
            }

            // Set tags
            const tagIds = data.tag_ids || [];
            document.querySelectorAll('input[name="tag_ids[]"]').forEach(cb => {
                cb.checked = tagIds.includes(parseInt(cb.value));
            });
            
            showModal();
        }

        function toggleQuickCategoryForm() {
            const container = document.getElementById('quickCategoryContainer');
            if (container) {
                if (container.style.display === 'none' || container.classList.contains('hidden')) {
                    container.classList.remove('hidden');
                    container.style.display = 'flex';
                    document.getElementById('quick_nama_kategori').focus();
                } else {
                    container.style.display = 'none';
                }
            }
        }

        function submitQuickCategory() {
            const nameInput = document.getElementById('quick_nama_kategori');
            const name = nameInput ? nameInput.value.trim() : '';

            if (!name) {
                showToast('Nama kategori tidak boleh kosong.', 'error');
                return;
            }

            fetch("{{ route('admin.categories.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    nama_kategori: name
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errData => {
                        throw new Error(errData.message || 'Terjadi kesalahan validasi.');
                    }).catch(() => {
                        throw new Error('Gagal memproses respons server.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');

                    // Add to dropdown and select it
                    const select = document.getElementById('id_kategori');
                    const opt = document.createElement('option');
                    opt.value = data.category.id_kategori;
                    opt.textContent = data.category.nama_kategori;
                    select.appendChild(opt);
                    select.value = data.category.id_kategori;
                    if (typeof select.refreshPremiumSelect === 'function') {
                        select.refreshPremiumSelect();
                    }

                    // Reset input & hide
                    nameInput.value = '';
                    const container = document.getElementById('quickCategoryContainer');
                    if (container) {
                        container.style.display = 'none';
                    }
                } else {
                    showToast(data.message || 'Gagal menambahkan kategori.', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding category:', error);
                showToast(error.message || 'Terjadi kesalahan saat menambahkan kategori.', 'error');
            });
        }

        function showModal() {
            const m = document.getElementById('linkModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.classList.add('modal-open');
        }

        function closeModal() {
            const m = document.getElementById('linkModal');
            if (!m) return;
            m.classList.add('closing');
            setTimeout(() => {
                m.classList.add('hidden');
                m.classList.remove('flex', 'closing');
                document.body.classList.remove('modal-open');
            }, 300);
        }

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('linkModal')) closeModal();
        });

        document.addEventListener('DOMContentLoaded', function() {
            initializeViewModeToggle('links');
        });
    </script>
@endsection
