{{--
|--------------------------------------------------------------------------
| Admin – Kelola Kategori
|--------------------------------------------------------------------------
| CRUD kategori + asosiasi layanan (link) ke kategori.
| Fitur: pencarian, grid kartu, preview layanan, modal tambah/edit.
| Data: $categories (Collection), $allLinks, $search.
|--------------------------------------------------------------------------
--}}
@extends('layouts.admin')

@section('content')
    @php
        $iconPaths = [
            'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>',
            'grid' => '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
            'sparkles' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>',
            'user' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>',
            'chain' => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>',
            'folder' => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>',
            'tag' => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line>',
            'book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>',
            'globe' => '<circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>',
            'settings' => '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>',
            'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
            'heart' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>',
        ];
    @endphp

    {{-- Header: judul + tombol tambah --}}
    <div class="categories-header">
        <div class="categories-title-wrap">
            <h1 class="categories-title">Kategori</h1>
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
            <button type="button" class="btn-add" onclick="addCategory()">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1V13M1 7H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Tambah Kategori
            </button>
        </div>
    </div>

    {{-- Pencarian kategori --}}
    <div class="search-container">
        <form action="{{ route('admin.categories') }}" method="GET" class="search-input-wrap">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="q" value="{{ $search }}" class="search-input" placeholder="Cari kategori...">
        </form>
    </div>

    @if ($categories->isNotEmpty())
        <div class="view-wrapper view-mode-table">
            <div class="view-table-container">
                {{-- Tabel data kategori --}}
                <div class="table-card mt-8 mb-6">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-16 pl-8">No</th>
                                <th>Nama Kategori</th>
                                <th class="w-36 text-center">Jumlah Layanan</th>
                                <th>Layanan Terkait</th>
                                <th class="w-36 text-center pr-8">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $cat)
                                <tr data-links="{{ json_encode($cat->links->pluck('id_link')) }}">
                                    <td class="pl-8">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(8, 13, 95, 0.04); color: #080d5f; flex-shrink: 0;">
                                                @if($cat->icon && array_key_exists($cat->icon, $iconPaths))
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        {!! $iconPaths[$cat->icon] !!}
                                                    </svg>
                                                @else
                                                    <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo" style="width: 22px; height: 22px; object-fit: contain;">
                                                @endif
                                            </div>
                                            <span class="font-bold text-[#080d5f]">{{ $cat->nama_kategori }}</span>
                                        </div>
                                    <td class="text-center">
                                        <span class="category-badge">{{ $cat->links_count }} Layanan</span>
                                    </td>
                                    <td>
                                        @if($cat->links->isNotEmpty())
                                            <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                                @foreach($cat->links->take(2) as $link)
                                                    <span class="badge-tag">{{ $link->nama_web }}</span>
                                                @endforeach
                                                @if($cat->links->count() > 2)
                                                    <span class="text-gray-400 text-[10px] font-semibold">+{{ $cat->links->count() - 2 }} lainnya</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-300 text-[11px] italic">Tidak ada layanan</span>
                                        @endif
                                    </td>
                                    <td class="text-center pr-8">
                                        <div class="action-btns justify-center items-center">
                                            <button type="button" class="btn-action btn-edit shadow-sm" title="Edit" onclick="editCategory({{ $cat->id_kategori }}, '{{ $cat->nama_kategori }}', '{{ $cat->icon }}', this)">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('admin.categories.destroy', $cat->id_kategori) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete(this, 'Apakah Anda yakin ingin menghapus kategori &quot;{{ $cat->nama_kategori }}&quot;?')" class="inline">
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
                {{-- Grid kartu kategori --}}
                <div class="categories-grid mb-6">
                    @foreach ($categories as $cat)
                        <article class="category-card" data-id="{{ $cat->id_kategori }}" data-name="{{ $cat->nama_kategori }}" data-links="{{ json_encode($cat->links->pluck('id_link')) }}">
                            {{-- Tombol edit & hapus --}}
                            <div class="category-actions">
                                <button type="button" class="btn-mini-action btn-mini-edit" title="Edit" onclick="editCategory({{ $cat->id_kategori }}, '{{ $cat->nama_kategori }}', '{{ $cat->icon }}', this)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $cat->id_kategori) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete(this, 'Apakah Anda yakin ingin menghapus kategori &quot;{{ $cat->nama_kategori }}&quot;?')" class="inline">
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

                            {{-- Ikon kategori --}}
                            <div class="category-icon-box" style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 12px; background: rgba(8, 13, 95, 0.04); color: #080d5f; margin-bottom: 16px;">
                                @if ($cat->icon && array_key_exists($cat->icon, $iconPaths))
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $iconPaths[$cat->icon] !!}
                                    </svg>
                                @else
                                    <img src="{{ asset('images/logo-polibatam.png') }}" alt="logo" style="width: 32px; height: 32px; object-fit: contain;">
                                @endif
                            </div>

                            <h2 class="category-name">{{ $cat->nama_kategori }}</h2>

                            <div class="service-badge">
                                {{ $cat->links_count }} Layanan
                            </div>

                            {{-- Preview layanan terkait --}}
                            @if($cat->links->isNotEmpty())
                            <div class="category-links-preview">
                                <span class="preview-title">Layanan Terkait:</span>
                                <ul class="preview-list">
                                    @foreach($cat->links->take(3) as $link)
                                        <li class="preview-item">{{ $link->nama_web }}</li>
                                    @endforeach
                                    @if($cat->links->count() > 3)
                                        <li class="preview-more">+{{ $cat->links->count() - 3 }} lainnya</li>
                                    @endif
                                </ul>
                            </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mb-12">
            {{ $categories->links('partials.pagination') }}
        </div>
    @else
        <div class="table-card p-12 text-center opacity-50 mt-10">
            Tidak ada kategori ditemukan.
        </div>
    @endif

    @push('modals')
    {{-- ═══════════════════════════════════════════════════════
         MODAL: Tambah/Edit Kategori (Premium Style)
         ═══════════════════════════════════════════════════════ --}}
    <div id="categoryModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell">
            <div class="premium-modal-card">
                <button type="button" onclick="closeModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <h2 id="modalTitle" class="premium-modal-title">Tambah Kategori</h2>

                <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div id="methodField"></div>

                    <div class="premium-modal-form-group">
                        <label for="nama_kategori" class="premium-modal-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" required class="premium-modal-input" placeholder="Contoh: Layanan Akademik">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Ikon Kategori</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; padding: 12px; background: rgba(8, 13, 95, 0.02); border: 1.5px dashed rgba(8, 13, 95, 0.1); border-radius: 12px;" id="icon-picker-container">
                            <label class="icon-option-label" style="cursor: pointer; display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 8px; border: 2px solid transparent; background: white; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                <input type="radio" name="icon" value="" checked style="display: none;" onchange="selectCategoryIcon(this)">
                                <img src="{{ asset('images/logo-polibatam.png') }}" alt="Default" style="width: 24px; height: 24px; object-fit: contain;">
                            </label>
                            @foreach(['home', 'grid', 'sparkles', 'user', 'chain', 'folder', 'tag', 'book', 'globe', 'settings', 'briefcase', 'heart'] as $iconName)
                                <label class="icon-option-label" data-icon-value="{{ $iconName }}" style="cursor: pointer; display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 8px; border: 2px solid transparent; background: white; transition: all 0.2s; color: #475569; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <input type="radio" name="icon" value="{{ $iconName }}" style="display: none;" onchange="selectCategoryIcon(this)">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $iconPaths[$iconName] !!}
                                    </svg>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Pilih Layanan (Link)</label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid rgba(8, 13, 95, 0.08); border-radius: 12px; padding: 12px; background: #f8fafc;">
                            @foreach ($allLinks as $link)
                                <label class="modal-link-item" onclick="toggleLinkHighlight(this)" style="display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 8px; cursor: pointer; transition: all 0.2s; margin-bottom: 4px;">
                                    <input type="checkbox" name="link_ids[]" value="{{ $link->id_link }}" class="link-checkbox" onchange="toggleLinkHighlight(this.parentElement)" style="width: 16px; height: 16px; accent-color: #080d5f;">
                                    <span class="modal-link-label" style="font-size: 13px; font-weight: 600; color: #1e2243;">{{ $link->nama_web }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="premium-modal-actions">
                        <button type="button" onclick="closeModal()" class="premium-modal-btn btn-cancel">Batal</button>
                        <button type="submit" class="premium-modal-btn btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush

    {{-- ═══════════════════════════════════════════════════════
         SCRIPT: Logika modal kategori
         ═══════════════════════════════════════════════════════ --}}
    <script>
        function selectCategoryIcon(radio) {
            document.querySelectorAll('.icon-option-label').forEach(lbl => {
                lbl.style.borderColor = 'transparent';
                lbl.style.background = 'white';
                lbl.style.color = '#475569';
            });
            const parent = radio.parentElement;
            parent.style.borderColor = '#080d5f';
            parent.style.background = 'rgba(8, 13, 95, 0.05)';
            parent.style.color = '#080d5f';
        }

        function toggleLinkHighlight(element) {
            const checkbox = element.querySelector('.link-checkbox');
            element.classList.toggle('is-selected', checkbox.checked);
        }

        function updateBodyLock() {
            const anyActive = !!document.querySelector('.premium-modal-overlay:not(.hidden)');
            document.body.classList.toggle('modal-open', anyActive);
        }

        function addCategory() {
            document.getElementById('modalTitle').innerText = 'Tambah Kategori';
            document.getElementById('categoryForm').action = "{{ route('admin.categories.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('nama_kategori').value = '';
            
            const defaultRadio = document.querySelector('input[name="icon"][value=""]');
            if (defaultRadio) {
                defaultRadio.checked = true;
                selectCategoryIcon(defaultRadio);
            }

            document.querySelectorAll('.link-checkbox').forEach(cb => {
                cb.checked = false;
                toggleLinkHighlight(cb.parentElement);
            });
            const m = document.getElementById('categoryModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
            updateBodyLock();
        }

        function editCategory(id, name, iconValue, btn) {
            document.getElementById('modalTitle').innerText = 'Edit Kategori';
            document.getElementById('categoryForm').action = "/admin/categories/" + id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('nama_kategori').value = name;

            const radio = document.querySelector(`input[name="icon"][value="${iconValue || ''}"]`);
            if (radio) {
                radio.checked = true;
                selectCategoryIcon(radio);
            }

            const container = btn.closest('[data-links]');
            const linkedIds = JSON.parse(container ? container.dataset.links : '[]');
            document.querySelectorAll('.link-checkbox').forEach(cb => {
                cb.checked = linkedIds.includes(parseInt(cb.value));
                toggleLinkHighlight(cb.parentElement);
            });
            const m = document.getElementById('categoryModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
            updateBodyLock();
        }

        function closeModal() {
            const m = document.getElementById('categoryModal');
            if (!m) return;
            m.classList.add('closing');
            setTimeout(() => {
                m.classList.add('hidden');
                m.classList.remove('flex', 'closing');
                updateBodyLock();
            }, 300);
        }

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('categoryModal')) closeModal();
        });

        document.addEventListener('DOMContentLoaded', function() {
            initializeViewModeToggle('categories');
        });
    </script>
@endsection
