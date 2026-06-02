{{--
|--------------------------------------------------------------------------
| Admin – Semua Layanan (Services)
|--------------------------------------------------------------------------
| Tampilan grid kartu semua layanan (read-only dari admin).
| Fitur: pencarian, filter kategori, kartu layanan dengan status & tag.
| Data: $services (array), $search, $categories, $selectedCategory.
|--------------------------------------------------------------------------
--}}
@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="services-heading mb-0">Semua Layanan</h1>
        
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
    </div>

    {{-- Toolbar: pencarian + filter kategori --}}
    <form method="GET" action="{{ route('admin.services') }}" class="services-toolbar" autocomplete="off">
        <label class="services-search" aria-label="Cari layanan">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M10.5 4.5a6 6 0 1 1 0 12a6 6 0 0 1 0-12Zm0 0l8.5 8.5" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" />
            </svg>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari layanan">
        </label>

        <label class="services-select-wrap" aria-label="Pilih kategori">
            <select name="category" class="services-select" onchange="this.form.submit()">
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                @endforeach
            </select>
            <span class="services-select-caret" aria-hidden="true"></span>
        </label>
    </form>

    <div class="view-wrapper view-mode-table">
        <div class="view-table-container">
            {{-- Table View --}}
            <div class="table-card mt-8">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="w-16 pl-8">No</th>
                            <th>Logo & Nama Layanan</th>
                            <th>Deskripsi</th>
                            <th>Kategori</th>
                            <th class="w-32 text-center">Status</th>
                            <th class="w-36 text-center pr-8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $index => $service)
                            <tr>
                                <td class="pl-8">{{ $index + 1 }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center border border-gray-100 flex-shrink-0">
                                            <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo" style="width: 20px; height: 20px; object-fit: contain;">
                                        </div>
                                        <span class="font-bold text-gray-800">{{ $service['title'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-500 max-w-sm truncate" title="{{ $service['description'] }}">
                                        {{ $service['description'] }}
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">{{ $service['category'] ?: '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $service['is_online'] ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $service['is_online'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $service['is_online'] ? 'Online' : 'Offline' }}
                                    </span>
                                </td>
                                <td class="text-center pr-8">
                                    <a href="{{ $service['url'] }}" class="btn-action shadow-sm inline-flex items-center justify-center {{ $service['url'] === '#' ? 'pointer-events-none opacity-50' : '' }}" target="_blank" title="Kunjungi">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">Belum ada layanan yang cocok dengan filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="view-card-container">
            {{-- Grid kartu layanan --}}
            <section class="services-grid" aria-label="Daftar semua layanan">
                @forelse ($services as $service)
                    <article class="service-card">
                        <div class="card-header-premium">
                            <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo" class="card-logo-img">
                            <h2 class="card-title-premium">{{ $service['title'] }}</h2>
                        </div>

                        <p class="card-description-premium">{{ $service['description'] }}</p>

                        <div class="card-footer-premium">
                            <div class="footer-left">
                                <span class="premium-status-dot {{ $service['is_online'] ? '' : 'offline' }}"></span>
                                <span class="text-[10px] font-semibold {{ $service['is_online'] ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $service['is_online'] ? 'Online' : 'Offline' }}
                                </span>
                            </div>

                            <div class="footer-right-group">
                                <a href="{{ $service['url'] }}"
                                    class="premium-visit-btn {{ $service['url'] === '#' ? 'is-disabled' : '' }}" target="_blank"
                                    rel="noreferrer">
                                    <span>Kunjungi</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="service-card-empty">Belum ada layanan yang cocok dengan filter ini.</div>
                @endforelse
            </section>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeViewModeToggle('services');
        });
    </script>
    @endpush
@endsection