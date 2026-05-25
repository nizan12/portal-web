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
    <h1 class="services-heading">Semua Layanan</h1>

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
@endsection