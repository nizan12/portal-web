{{-- Kartu layanan --}}
<article class="service-card" data-service-card-item data-title="{{ $service['title'] }}" data-url="{{ $service['url'] }}" data-category="{{ $service['category'] }}">
    {{-- Bookmark Icon --}}
    <button type="button" class="card-bookmark" data-service-bookmark-toggle aria-label="Simpan layanan">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21l-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"></path>
        </svg>
    </button>

    {{-- Header: Logo & Title --}}
    <div class="card-header-premium">
        <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo" class="card-logo-img">
        <h2 class="card-title-premium">{{ $service['title'] }}</h2>
        @if ($service['is_custom'])
            <div class="card-action-group">
                {{-- Tombol edit link pribadi --}}
                <button type="button"
                    onclick="openLinkModal('{{ $service['id'] }}', '{{ $service['title'] }}', '{{ $service['url'] }}', '{{ $service['description'] }}', '{{ $service['role'] ?? '' }}', '{{ json_encode($service['tag_ids'] ?? []) }}')"
                    class="card-action-btn edit-btn"
                    title="Edit Link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"></path></svg>
                </button>
                {{-- Tombol hapus link pribadi --}}
                <form action="{{ route('pengguna.links.destroy', $service['id']) }}" method="POST" onsubmit="return confirm('Hapus link ini?')" class="m-0 p-0 flex">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="card-action-btn delete-btn" title="Hapus Link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Description --}}
    <p class="card-description-premium">{{ $service['description'] }}</p>

    {{-- Admin Tags --}}
    @if (!empty($service['tags']))
        <div class="flex flex-wrap gap-1 mb-2">
            @foreach ($service['tags'] as $tagName)
                <div class="premium-tag-btn">
                    <span class="tag-icon-circle"></span>
                    <span>{{ $tagName }}</span>
                </div>
            @endforeach
        </div>
    @elseif (!empty($service['tag']))
        {{-- Fallback to old system if tags relationship is empty but tag column is not --}}
        <div class="flex flex-wrap gap-1 mb-2">
            @foreach (explode(',', $service['tag']) as $tagName)
                @php $tagName = trim($tagName); @endphp
                @if ($tagName !== '')
                    <div class="premium-tag-btn">
                        <span class="tag-icon-circle"></span>
                        <span>{{ $tagName }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    {{-- Footer Actions --}}
    <div class="card-footer-premium">
        <div class="footer-left">
            <span class="premium-status-dot {{ $service['status'] === 'aktif' ? '' : 'offline' }}" aria-hidden="true"></span>
            <span class="text-[10px] font-semibold {{ $service['status'] === 'aktif' ? 'text-green-600' : 'text-red-600' }}">
                {{ $service['status'] === 'aktif' ? 'Online' : 'Offline' }}
            </span>
        </div>

        <div class="footer-right-group">
            <a
                href="{{ $service['url'] }}"
                target="_blank"
                rel="noopener"
                class="premium-visit-btn"
                aria-label="Kunjungi {{ $service['title'] }}"
            >
                <span>Kunjungi</span>
            </a>

            <div class="footer-category-wrapper">
                <button
                    type="button"
                    class="premium-category-btn"
                    data-card-dropdown-toggle
                >
                    <span>+ Kategori</span>
                </button>

                <div class="card-dropdown" data-card-dropdown-menu>
                    <div class="card-dropdown-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.8 18.1a7.3 7.3 0 1 1 0-14.6 7.3 7.3 0 0 1 0 14.6Zm6-1.3 3.7 3.7" stroke-linecap="round" />
                        </svg>
                        <input type="text" placeholder="Cari..." data-card-category-search>
                    </div>
                    
                    <div class="card-dropdown-list">
                    <div class="card-dropdown-section-title">Kategori</div>
                    @foreach ($categories as $categoryOption)
                        <button type="button" class="card-dropdown-item {{ strtolower($service['category']) === strtolower($categoryOption) ? 'is-selected' : '' }}" data-card-category-option="{{ $categoryOption }}">
                            <div class="card-dropdown-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H10l2 2h5.5A2.5 2.5 0 0 1 20 9.5v7a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9Z" />
                                </svg>
                            </div>
                            <span class="card-dropdown-item-label">{{ $categoryOption }}</span>
                            <div class="card-dropdown-item-circle"></div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</article>
