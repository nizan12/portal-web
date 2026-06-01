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
    {{-- Header: judul + tombol tambah --}}
    <div class="categories-header">
        <div class="categories-title-wrap">
            <h1 class="categories-title">Kategori</h1>
        </div>
        <button type="button" class="btn-add" onclick="addCategory()">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 1V13M1 7H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Tambah Kategori
        </button>
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

    {{-- Grid kartu kategori --}}
    <div class="categories-grid">
        @forelse ($categories as $cat)
            <article class="category-card" data-id="{{ $cat->id_kategori }}" data-name="{{ $cat->nama_kategori }}" data-links="{{ json_encode($cat->links->pluck('id_link')) }}">
                {{-- Tombol edit & hapus --}}
                <div class="category-actions">
                    <button type="button" class="btn-mini-action btn-mini-edit" title="Edit" onclick="editCategory({{ $cat->id_kategori }}, '{{ $cat->nama_kategori }}', this)">
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
                                <path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Ikon kategori --}}
                <div class="category-icon-box">
                    @if (str_contains(strtolower($cat->nama_kategori), 'akadem'))
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                    @else
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                    @endif
                </div>

                <h2 class="category-name">{{ $cat->nama_kategori }}</h2>
                <p class="category-desc">
                    Kategori {{ $cat->nama_kategori }} menyediakan berbagai layanan yang mendukung proses operasional dan layanan digital di lingkungan Politeknik Negeri Batam.
                </p>

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
        @empty
            <div class="empty-state">
                <p>Tidak ada kategori ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════════════════════
         MODAL: Tambah/Edit Kategori
         Form nama kategori + pilih layanan (checkbox).
         ═══════════════════════════════════════════════════════ --}}
    <div id="categoryModal" class="hidden fixed inset-0 z-[1000] bg-black/50 items-center justify-center">
        <div class="bg-white p-[30px] rounded-2xl w-full max-w-[450px] shadow-2xl">
            <h2 id="modalTitle" class="m-0 mb-5 text-xl text-[#080d5f]">Tambah Kategori</h2>
            <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="mb-5">
                    <label for="nama_kategori" class="block text-xs font-semibold text-gray-500 mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" required class="w-full h-[45px] px-4 border border-gray-200 rounded-xl outline-none text-sm">
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Pilih Layanan (Link)</label>
                    <div class="max-h-[250px] overflow-y-auto border border-gray-200 rounded-[14px] p-3 bg-white shadow-inner">
                        @foreach ($allLinks as $link)
                            <label class="modal-link-item" onclick="toggleLinkHighlight(this)">
                                <input type="checkbox" name="link_ids[]" value="{{ $link->id_link }}" class="link-checkbox" onchange="toggleLinkHighlight(this.parentElement)">
                                <span class="modal-link-label">{{ $link->nama_web }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-2.5 justify-end">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-[10px] border border-gray-200 bg-white cursor-pointer font-semibold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-[10px] border-0 bg-[#080d5f] text-white cursor-pointer font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SCRIPT: Logika modal kategori
         ═══════════════════════════════════════════════════════ --}}
    <script>
        function toggleLinkHighlight(element) {
            const checkbox = element.querySelector('.link-checkbox');
            element.classList.toggle('is-selected', checkbox.checked);
        }

        function addCategory() {
            document.getElementById('modalTitle').innerText = 'Tambah Kategori';
            document.getElementById('categoryForm').action = "{{ route('admin.categories.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('nama_kategori').value = '';
            document.querySelectorAll('.link-checkbox').forEach(cb => {
                cb.checked = false;
                toggleLinkHighlight(cb.parentElement);
            });
            const m = document.getElementById('categoryModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function editCategory(id, name, btn) {
            document.getElementById('modalTitle').innerText = 'Edit Kategori';
            document.getElementById('categoryForm').action = "/admin/categories/" + id;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            document.getElementById('nama_kategori').value = name;
            const card = btn.closest('.category-card');
            const linkedIds = JSON.parse(card.dataset.links || '[]');
            document.querySelectorAll('.link-checkbox').forEach(cb => {
                cb.checked = linkedIds.includes(parseInt(cb.value));
                toggleLinkHighlight(cb.parentElement);
            });
            const m = document.getElementById('categoryModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeModal() {
            const m = document.getElementById('categoryModal');
            if (!m) return;
            m.classList.add('closing');
            setTimeout(() => {
                m.classList.add('hidden');
                m.classList.remove('flex', 'closing');
            }, 300);
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('categoryModal')) closeModal();
        }
    </script>
@endsection
