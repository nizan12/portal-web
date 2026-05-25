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
                    <form action="{{ route('admin.tags.destroy', $tag->id_tag) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tag ini?')" class="inline">
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

    {{-- ═══════════════════════════════════════════════════════
         MODAL: Tambah/Edit Tag
         Form nama tag + pilih layanan (checkbox).
         ═══════════════════════════════════════════════════════ --}}
    <div id="tagModal" class="hidden fixed inset-0 z-[2000] bg-black/60 backdrop-blur-sm items-center justify-center overflow-y-auto p-5 transition-all duration-300">
        <div class="bg-white rounded-3xl w-full max-w-[450px] shadow-2xl my-auto overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="bg-[#080d5f] p-6 text-white flex justify-between items-center">
                <h2 id="modalTitle" class="m-0 text-xl font-bold tracking-tight">Tambah Tag</h2>
                <button type="button" onclick="closeModal()" class="text-white/70 hover:text-white transition-colors">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="tagForm" action="{{ route('admin.tags.store') }}" method="POST" class="p-8">
                @csrf
                <div id="methodField"></div>
                
                <div class="mb-6 space-y-2">
                    <label for="nama_tag" class="block text-xs font-bold text-[#080d5f] uppercase letter-spacing-[0.5px]">Nama Tag</label>
                    <input type="text" name="nama_tag" id="nama_tag" required 
                           class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl outline-none text-[14px] focus:border-[#080d5f] focus:bg-white transition-all"
                           placeholder="Contoh: Populer, Baru, Internal">
                </div>

                <div class="mb-8 space-y-2">
                    <label class="block text-xs font-bold text-[#080d5f] uppercase letter-spacing-[0.5px]">Terapkan pada Layanan</label>
                    <div class="max-h-[300px] overflow-y-auto border border-gray-200 rounded-2xl p-4 bg-gray-50/50 space-y-1">
                        @foreach ($allLinks as $link)
                            <label class="modal-link-item flex items-center gap-3 p-3 rounded-xl hover:bg-white hover:shadow-sm transition-all cursor-pointer group" onclick="toggleTagLinkHighlight(this)">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" name="link_ids[]" value="{{ $link->id_link }}" class="link-checkbox hidden" onchange="toggleTagLinkHighlight(this.parentElement.parentElement)">
                                    <div class="w-5 h-5 rounded-md border-2 border-gray-300 bg-white group-[.is-selected]:bg-[#080d5f] group-[.is-selected]:border-[#080d5f] transition-all flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-white opacity-0 group-[.is-selected]:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-[#080d5f] group-[.is-selected]:text-[#080d5f]">{{ $link->nama_web }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal()" 
                            class="px-6 py-3 rounded-xl border border-gray-200 bg-white cursor-pointer font-bold text-[14px] text-gray-500 hover:bg-gray-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-8 py-3 rounded-xl border-0 bg-[#080d5f] text-white cursor-pointer font-bold text-[14px] hover:bg-[#0c148c] shadow-lg shadow-blue-900/20 transition-all">
                        Simpan Tag
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SCRIPT: Logika modal tag
         ═══════════════════════════════════════════════════════ --}}
    <script>
        function toggleTagLinkHighlight(element) {
            const checkbox = element.querySelector('.link-checkbox');
            if (checkbox.checked) {
                element.classList.add('is-selected');
                element.classList.add('bg-white');
                element.classList.add('shadow-sm');
            } else {
                element.classList.remove('is-selected');
                element.classList.remove('bg-white');
                element.classList.remove('shadow-sm');
            }
        }

        function addTag() {
            document.getElementById('modalTitle').innerText = 'Tambah Tag';
            document.getElementById('tagForm').action = "{{ route('admin.tags.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('nama_tag').value = '';
            document.querySelectorAll('.link-checkbox').forEach(cb => {
                cb.checked = false;
                toggleTagLinkHighlight(cb.parentElement.parentElement);
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
                toggleTagLinkHighlight(cb.parentElement.parentElement);
            });
            showModal();
        }

        function showModal() {
            const m = document.getElementById('tagModal');
            const c = document.getElementById('modalContent');
            m.classList.remove('hidden');
            m.classList.add('flex');
            setTimeout(() => {
                c.classList.remove('scale-95', 'opacity-0');
                c.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const m = document.getElementById('tagModal');
            const c = document.getElementById('modalContent');
            c.classList.add('scale-95', 'opacity-0');
            c.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                m.classList.add('hidden');
                m.classList.remove('flex');
            }, 300);
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('tagModal')) closeModal();
        }
    </script>
@endsection
