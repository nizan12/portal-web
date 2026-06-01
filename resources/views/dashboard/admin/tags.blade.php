{{--
|--------------------------------------------------------------------------
| Admin – Kelola Tag
|--------------------------------------------------------------------------
| CRUD tag + asosiasi layanan (link) ke tag (Many-to-Many).
| Fitur: pencarian, grid kartu, preview layanan, modal tambah/edit.
| Data: $tags (Collection), $allLinks, $search.
|--------------------------------------------------------------------------
--}}
@extends('layouts.admin')

@section('content')
    {{-- Header: judul + tombol tambah --}}
    <div class="categories-header">
        <div class="categories-title-wrap">
            <h1 class="categories-title">Kelola Tag</h1>
            <span class="links-subtitle">{{ $tags->count() }} Tag Terdaftar</span>
        </div>
        <button type="button" class="btn-add" onclick="addTag()">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 1V13M1 7H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Tambah Tag
        </button>
    </div>

    {{-- Pencarian tag --}}
    <div class="search-container">
        <form action="{{ route('admin.tags') }}" method="GET" class="search-input-wrap">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="q" value="{{ $search }}" class="search-input" placeholder="Cari tag admin...">
        </form>
    </div>

    {{-- Grid kartu tag --}}
    <div class="categories-grid">
        @forelse ($tags as $tag)
            <article class="category-card" data-id="{{ $tag->id_tag }}" data-name="{{ $tag->nama_tag }}" data-links="{{ json_encode($tag->links->pluck('id_link')) }}">
                {{-- Tombol edit & hapus --}}
                <div class="category-actions">
                    <button type="button" class="btn-mini-action btn-mini-edit" title="Edit" onclick="editTag({{ $tag->id_tag }}, '{{ $tag->nama_tag }}', this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2-2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                    <form action="{{ route('admin.tags.destroy', $tag->id_tag) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete(this, 'Apakah Anda yakin ingin menghapus tag &quot;{{ $tag->nama_tag }}&quot;?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-mini-action btn-mini-delete" title="Hapus">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Ikon tag --}}
                <div class="category-icon-box bg-orange-50 text-orange-500">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                </div>

                <h2 class="category-name">{{ $tag->nama_tag }}</h2>
                <p class="category-desc">
                    Tag "{{ $tag->nama_tag }}" digunakan untuk memberikan label khusus pada layanan agar lebih mudah ditemukan dan diidentifikasi oleh pengguna di dashboard utama.
                </p>

                <div class="service-badge bg-orange-100 text-orange-700">
                    {{ $tag->links_count }} Layanan
                </div>

                {{-- Preview layanan terkait --}}
                @if($tag->links->isNotEmpty())
                <div class="category-links-preview">
                    <span class="preview-title">Diterapkan pada:</span>
                    <ul class="preview-list">
                        @foreach($tag->links->take(3) as $link)
                            <li class="preview-item">{{ $link->nama_web }}</li>
                        @endforeach
                        @if($tag->links->count() > 3)
                            <li class="preview-more">+{{ $tag->links->count() - 3 }} lainnya</li>
                        @endif
                    </ul>
                </div>
                @endif
            </article>
        @empty
            <div class="empty-state py-20 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 rounded-3xl opacity-60">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-4 text-gray-300">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
                <p class="text-sm font-medium">Tidak ada tag ditemukan.</p>
            </div>
        @endforelse
    </div>

    @push('modals')
    {{-- ═══════════════════════════════════════════════════════
         MODAL: Tambah/Edit Tag (Premium Style)
         ═══════════════════════════════════════════════════════ --}}
    <div id="tagModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell">
            <div class="premium-modal-card">
                <button type="button" onclick="closeModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <h2 id="modalTitle" class="premium-modal-title">Tambah Tag</h2>

                <form id="tagForm" action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf
                    <div id="methodField"></div>
                    
                    <div class="premium-modal-form-group">
                        <label for="nama_tag" class="premium-modal-label">Nama Tag</label>
                        <input type="text" name="nama_tag" id="nama_tag" required class="premium-modal-input" placeholder="Contoh: Populer, Baru, Internal">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Terapkan pada Layanan</label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid rgba(8, 13, 95, 0.08); border-radius: 12px; padding: 12px; background: #f8fafc;">
                            @foreach ($allLinks as $link)
                                <label class="modal-link-item" onclick="toggleTagLinkHighlight(this)" style="display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 8px; cursor: pointer; transition: all 0.2s; margin-bottom: 4px;">
                                    <input type="checkbox" name="link_ids[]" value="{{ $link->id_link }}" class="link-checkbox" onchange="toggleTagLinkHighlight(this.parentElement)" style="width: 16px; height: 16px; accent-color: #080d5f;">
                                    <span style="font-size: 13px; font-weight: 600; color: #1e2243;">{{ $link->nama_web }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="premium-modal-actions">
                        <button type="button" onclick="closeModal()" class="premium-modal-btn btn-cancel">Batal</button>
                        <button type="submit" class="premium-modal-btn btn-save">Simpan Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush
@endsection

@push('scripts')
    {{-- ═══════════════════════════════════════════════════════
         SCRIPT: Logika modal tag
         ═══════════════════════════════════════════════════════ --}}
    <script>
        function toggleTagLinkHighlight(element) {
            const checkbox = element.querySelector('.link-checkbox');
            if (checkbox.checked) {
                element.classList.add('is-selected');
                element.style.backgroundColor = '#fff';
                element.style.boxShadow = '0 4px 12px rgba(8, 13, 95, 0.04)';
            } else {
                element.classList.remove('is-selected');
                element.style.backgroundColor = '';
                element.style.boxShadow = '';
            }
        }

        function addTag() {
            document.getElementById('modalTitle').innerText = 'Tambah Tag';
            document.getElementById('tagForm').action = "{{ route('admin.tags.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('nama_tag').value = '';
            document.querySelectorAll('.link-checkbox').forEach(cb => {
                cb.checked = false;
                toggleTagLinkHighlight(cb.parentElement);
            });
            showModal();
        }

        function editTag(id, name, btn) {
            document.getElementById('modalTitle').innerText = 'Edit Tag';
            document.getElementById('tagForm').action = "/admin/tags/" + id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('nama_tag').value = name;
            const card = btn.closest('.category-card');
            const linkedIds = JSON.parse(card.dataset.links || '[]');
            document.querySelectorAll('.link-checkbox').forEach(cb => {
                cb.checked = linkedIds.includes(parseInt(cb.value));
                toggleTagLinkHighlight(cb.parentElement);
            });
            showModal();
        }

        function showModal() {
            const m = document.getElementById('tagModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeModal() {
            const m = document.getElementById('tagModal');
            if (!m) return;
            m.classList.add('closing');
            setTimeout(() => {
                m.classList.add('hidden');
                m.classList.remove('flex', 'closing');
            }, 300);
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('tagModal')) closeModal();
        }
    </script>
@endpush
