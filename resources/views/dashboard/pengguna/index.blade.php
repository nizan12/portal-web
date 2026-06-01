<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pengguna - {{ config('app.name', 'POLTREE') }}</title>

    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/css/pengguna.css', 'resources/js/app.js', 'resources/js/pengguna.js'])

    <script>
        window.PolTree = {
            availableServices: @json($allLinkTitles),
            userNik: '{{ auth('pengguna')->user()->nik }}',
            activeRole: '{{ $activeRole }}',
            storeUserLinkRoute: "{{ route('pengguna.links.store') }}"
        };
    </script>
</head>
<body>
    {{-- Container for premium global toasts --}}
    <div id="toastContainer" class="toast-container"></div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('success') }}", 'success');
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($errors->all() as $error)
                    showToast("{{ $error }}", 'error');
                @endforeach
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('error') }}", 'error');
            });
        </script>
    @endif

    <div class="dashboard-layout">
        {{-- ═══════════════════════════════════════════════════════
            SIDEBAR NAVIGATION
        ═══════════════════════════════════════════════════════ --}}
        <div class="sidebar-overlay" data-sidebar-overlay></div>
        <aside class="sidebar" data-sidebar>
            <div class="sidebar-brand">
                <span class="brand-dot"></span>
                <span>Pol<span class="brand-orange">Tree</span></span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('pengguna.dashboard') }}" class="sidebar-link active" data-sidebar-beranda>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Beranda</span>
                </a>

                <button type="button" class="sidebar-link" data-all-services-btn>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                    <span>Semua Layanan</span>
                </button>

                <button type="button" class="sidebar-link" data-sidebar-kategori>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3h7v7H3V3Zm11 0h7v7h-7V3ZM3 14h7v7H3v-7Zm11 0h7v7h-7v-7Z"/>
                    </svg>
                    <span>Kategori</span>
                </button>
            </nav>
        </aside>

        {{-- ═══════════════════════════════════════════════════════
            MAIN CONTENT AREA
        ═══════════════════════════════════════════════════════ --}}
        <div class="main-content">
            {{-- Topbar --}}
            <header class="topbar">
                <div class="topbar-inner">
                    <button type="button" class="mobile-menu-btn" data-mobile-menu-toggle aria-label="Menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>

                    <form class="search-box" role="search" action="{{ route('pengguna.dashboard') }}" method="GET">
                        <input type="hidden" name="role" value="{{ $activeRole }}">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M10.8 18.1a7.3 7.3 0 1 1 0-14.6 7.3 7.3 0 0 1 0 14.6Zm6-1.3 3.7 3.7" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <input type="text" name="q" value="{{ $search }}" placeholder="Cari.." aria-label="Cari">
                        <button type="submit">Cari</button>
                    </form>

                    <div class="profile-menu-wrap">
                        <button type="button" class="profile-icon" aria-label="Profil pengguna" aria-expanded="false" data-profile-toggle>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 12.2a4.4 4.4 0 1 0 0-8.8 4.4 4.4 0 0 0 0 8.8Zm-7 8.4c.9-3.5 3.5-5.4 7-5.4s6.1 1.9 7 5.4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </button>

                        <div class="profile-panel" data-profile-panel hidden>
                            <div class="profile-panel-actions">
                                <button type="button" class="profile-panel-btn" onclick="openProfileModal()">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                                    </svg>
                                    <span>Profil</span>
                                </button>

                                <button type="button" class="profile-panel-btn" onclick="openPasswordModal()">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <span>Ubah Kata Sandi</span>
                                </button>

                                <form action="{{ route('logout') }}" method="POST" class="profile-panel-form">
                                    @csrf
                                    <button type="submit" class="profile-panel-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4m7 14l5-5-5-5m5 5H9" />
                                        </svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- ═══════════════════════════════════════════════════════
                MODAL: Ubah Kata Sandi Pengguna
            ═══════════════════════════════════════════════════════ --}}
            <div id="passwordModal" class="hidden premium-modal-overlay">
                <div class="premium-modal-shell">
                    <div class="premium-modal-card">
                        <button type="button" onclick="closePasswordModal()" class="premium-modal-close-btn" aria-label="Tutup">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>

                        <h2 class="premium-modal-title">Ubah Kata Sandi</h2>

                        <form action="{{ route('pengguna.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="premium-modal-form-group">
                                <label class="premium-modal-label">Kata Sandi Lama</label>
                                <input type="password" name="old_password" required class="premium-modal-input" placeholder="Masukkan kata sandi lama">
                            </div>

                            <div class="premium-modal-form-group">
                                <label class="premium-modal-label">Kata Sandi Baru</label>
                                <input type="password" name="password" required class="premium-modal-input" placeholder="Masukkan kata sandi baru">
                            </div>

                            <div class="premium-modal-form-group">
                                <label class="premium-modal-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" required class="premium-modal-input" placeholder="Ulangi kata sandi baru">
                            </div>

                            <div class="premium-modal-actions">
                                <button type="button" onclick="closePasswordModal()" class="premium-modal-btn btn-cancel">Batal</button>
                                <button type="submit" class="premium-modal-btn btn-save">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="content-area">
                {{-- ═══════════════════════════════════════════════════════
                    HERO BANNER
                ═══════════════════════════════════════════════════════ --}}
                <section class="hero-banner" aria-label="Foto kampus Politeknik Negeri Batam">
                    @php
                        $heroStep = 3;
                        $heroDuration = max($heroImages->count() * $heroStep, $heroStep);
                    @endphp

                    @foreach ($heroImages as $index => $image)
                        <span
                            class="hero-slide {{ $heroImages->count() === 1 ? 'only' : '' }}"
                            style="background-image: url('{{ $image }}'); --hero-duration: {{ $heroDuration }}s; animation-delay: -{{ $index * $heroStep }}s;"
                            aria-hidden="true"
                        ></span>
                    @endforeach

                    <div class="hero-text">
                        <h1>Selamat Datang, <span class="hero-name">{{ auth('pengguna')->user()->nama ?? 'Pengguna' }}</span></h1>
                        <p>Akses semua layanan Polibatam lebih mudah dalam satu tempat.</p>
                    </div>
                </section>

                {{-- ═══════════════════════════════════════════════════════
                    TAB NAVIGATION
                ═══════════════════════════════════════════════════════ --}}
                <nav class="tab-nav" aria-label="Tab navigasi">
                    <button type="button" class="tab-btn active" data-shortcut-saved-toggle>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path d="M5 5h14v16l-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" stroke-linejoin="round" />
                        </svg>
                        <span>Tersimpan</span>
                    </button>

                    <button type="button" class="tab-btn" data-tab-kategori>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M3 3h7v7H3V3Zm11 0h7v7h-7V3ZM3 14h7v7H3v-7Zm11 0h7v7h-7v-7Z" />
                        </svg>
                        <span>Kategori</span>
                    </button>
                </nav>

                {{-- Active Category Filter Banner --}}
                <div id="category-filter-indicator" class="filter-indicator-bar" style="display: none;">
                    <div class="filter-indicator-content">
                        <span class="filter-indicator-dot"></span>
                        <span class="filter-indicator-text">Kategori Aktif: <strong data-shortcut-category-label></strong></span>
                    </div>
                    <button type="button" class="filter-indicator-clear" id="clear-category-filter-btn" aria-label="Hapus filter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                {{-- ═══════════════════════════════════════════════════════
                    VIEW: TERSIMPAN
                ═══════════════════════════════════════════════════════ --}}
                <div id="view-tersimpan" class="section-block">
                    {{-- Layanan Utama --}}
                    @if (!$adminServices->isEmpty())
                        <div class="section-block" data-section="official">
                            <div class="section-header">
                                <div class="section-header-left">
                                    <h2><span class="section-title-highlight">Lay</span>anan Resmi</h2>
                                    <p>Akses cepat ke layanan resmi Politeknik Negeri Batam</p>
                                </div>
                            </div>
                            <div class="cards-grid">
                                @foreach ($adminServices as $service)
                                    @include('dashboard.partials.service_card', ['service' => $service])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Layanan Pribadi --}}
                    <div class="section-block" data-section="personal">
                        <div class="section-header">
                            <div class="section-header-left">
                                <h2>Layanan Pribadi</h2>
                                <p>Tautan website kustom buatan Anda sendiri</p>
                            </div>
                            <button type="button" class="section-add-btn" onclick="openLinkModal()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Link</span>
                            </button>
                        </div>
                        
                        @if (!$userServices->isEmpty())
                            <div class="cards-grid">
                                @foreach ($userServices as $service)
                                    @include('dashboard.partials.service_card', ['service' => $service])
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-10 bg-white rounded-2xl border border-dashed border-gray-200 text-gray-400">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 opacity-40">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                </svg>
                                <p class="text-sm font-medium">Belum ada link pribadi ditambahkan</p>
                            </div>
                        @endif
                    </div>

                    {{-- Premium Empty State --}}
                    <div id="empty-state-card" class="empty-state-container" data-shortcut-empty style="display: none;">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                        </div>
                        <h3 class="empty-state-title">Tidak Ada Layanan Ditemukan</h3>
                        <p class="empty-state-desc">Silakan periksa kategori lain atau tambahkan link pribadi baru.</p>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════
                    VIEW: KATEGORI
                ═══════════════════════════════════════════════════════ --}}
                <div id="view-kategori" class="section-block hidden">
                    <div class="section-header">
                        <div class="section-header-left">
                            <h2>Kategori Layanan</h2>
                            <p>Temukan layanan berdasarkan klasifikasi folder</p>
                        </div>
                    </div>

                    <div class="folder-grid">
                        @foreach ($categoriesList as $category)
                            @php
                                $catLinks = $category->links;
                                $totalLinks = $catLinks->count();
                                $displayLinks = $catLinks->take(4);
                            @endphp
                            <div class="folder-card" data-category-folder="{{ $category->nama_kategori }}">
                                {{-- Tombol edit folder kategori --}}
                                <button type="button" class="folder-edit-btn" data-category-edit-toggle="{{ $category->nama_kategori }}" aria-label="Edit Kategori">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"></path>
                                    </svg>
                                </button>
                                <div class="folder-header">
                                    <div class="folder-icon-grid">
                                        @foreach ($displayLinks as $idx => $link)
                                            @if ($idx === 3 && $totalLinks > 4)
                                                <div class="folder-sub-icon overflow-badge">
                                                    <span>+{{ $totalLinks - 3 }}</span>
                                                </div>
                                            @else
                                                @php
                                                    $lowerTitle = strtolower($link->nama_web);
                                                    $lowerUrl = strtolower($link->url);
                                                    $isPolibatam = str_contains($lowerTitle, 'polibatam') || str_contains($lowerUrl, 'polibatam');
                                                    $initials = '';
                                                    if (!$isPolibatam) {
                                                        $words = explode(' ', preg_replace('/[^a-zA-Z0-9\s]/', '', $link->nama_web));
                                                        if (count($words) >= 2) {
                                                            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                                        } else {
                                                            $initials = strtoupper(substr($link->nama_web, 0, 2));
                                                        }
                                                    }
                                                @endphp
                                                <div class="folder-sub-icon">
                                                    @if ($isPolibatam)
                                                        <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo" class="sub-icon-img">
                                                    @else
                                                        <span class="sub-icon-text">{{ $initials }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                        @for ($i = $totalLinks; $i < 4; $i++)
                                            <div class="folder-sub-icon empty-slot"></div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="folder-body">
                                    <h3 class="folder-title">{{ $category->nama_kategori }}</h3>
                                    <p class="folder-count">{{ $totalLinks }} Layanan</p>
                                </div>
                            </div>
                        @endforeach

                        {{-- Tambah Kategori Card --}}
                        <div class="folder-card dashed-folder" onclick="openCategoryBuilder('shortcut')">
                            <div class="folder-header">
                                <div class="dashed-folder-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </div>
                            </div>
                            <div class="folder-body">
                                <h3 class="folder-title">Tambah Kategori</h3>
                                <p class="folder-count">Buat baru</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($services->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-4 opacity-20"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <p class="text-lg font-medium">Belum ada layanan tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
        CATEGORY BUILDER MODAL
    ═══════════════════════════════════════════════════════ --}}
    <section class="category-builder-modal" aria-hidden="true" data-category-builder-modal>
        <div class="category-builder-shell">
            <div class="category-builder-card" role="dialog" aria-modal="true" aria-labelledby="category-builder-title-label">
                <div class="category-builder-header">
                    <label class="category-builder-title-wrap">
                        <span id="category-builder-title-label" class="sr-only">Judul kategori baru</span>
                        <input type="text" class="category-builder-title" placeholder="Tambahkan Judul Kategori.." data-category-builder-title>
                        <svg class="category-builder-title-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m4 20 4.5-1 9-9a2.1 2.1 0 1 0-3-3l-9 9L4 20Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                            <path d="m13.5 6.5 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </label>

                    <button type="button" class="category-builder-reset" data-category-builder-reset aria-label="Reset kategori baru">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 7h14M9 7V5h6v2M8 7v11m8-11v11M6 7l1 13a1 1 0 0 0 1 .9h8a1 1 0 0 0 1-.9l1-13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <div class="category-builder-body">
                    <button type="button" class="category-builder-link-add" data-category-builder-link-add>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah Link</span>
                    </button>

                    <div class="category-builder-links" data-category-builder-links></div>
                    <p class="category-builder-empty" data-category-builder-empty>Belum ada link ditambahkan</p>
                </div>

                <div class="category-builder-footer">
                    <button type="button" class="category-builder-save" data-category-builder-save>Simpan Kategori</button>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
        MODAL: CRUD Link Pribadi
    ═══════════════════════════════════════════════════════ --}}
    <div id="linkModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell">
            <div class="premium-modal-card">
                <button type="button" onclick="closeLinkModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <h2 id="linkModalTitle" class="premium-modal-title">Tambah Link</h2>
                <form id="linkForm" action="{{ route('pengguna.links.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="linkMethod" value="POST">

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Nama Website</label>
                        <input type="text" name="nama_web" id="linkTitle" placeholder="Contoh: My Portal" required class="premium-modal-input">
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">URL Website</label>
                        <input type="url" name="url" id="linkUrl" placeholder="https://example.com" required class="premium-modal-input">
                    </div>

                    <input type="hidden" name="role" id="linkRole" value="">

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Tag (Opsional)</label>
                        <div class="premium-modal-tags-wrapper">
                            @foreach ($allAdminTags as $tag)
                                <label class="premium-modal-tag-pill">
                                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id_tag }}" class="user-tag-checkbox">
                                    <span>{{ $tag->nama_tag }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="premium-modal-form-group">
                        <label class="premium-modal-label">Deskripsi</label>
                        <textarea name="deskripsi" id="linkDescription" placeholder="Deskripsi singkat..." class="premium-modal-textarea"></textarea>
                    </div>

                    <div class="premium-modal-actions">
                        <button type="button" onclick="closeLinkModal()" class="premium-modal-btn btn-cancel">Batal</button>
                        <button type="submit" class="premium-modal-btn btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
        MODAL: Konfirmasi Hapus (Global - Premium Style)
    ═══════════════════════════════════════════════════════ --}}
    <div id="confirmDeleteModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell">
            <div class="premium-modal-card">
                <button type="button" onclick="closeConfirmModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                
                <h2 class="premium-modal-title">Konfirmasi Hapus</h2>
                
                <div style="text-align: center; margin-bottom: 24px;">
                    <div style="width: 56px; height: 56px; margin: 0 auto 16px; border-radius: 50%; background: #fff0ed; display: flex; align-items: center; justify-content: center;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#ff3f0a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <p id="confirmDeleteMessage" style="font-size: 14px; color: #555b77; line-height: 1.6; font-weight: 500;">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                
                <div class="premium-modal-actions">
                    <button type="button" class="premium-modal-btn btn-cancel" onclick="closeConfirmModal()">Batal</button>
                    <button type="button" class="premium-modal-btn btn-save" style="background: #ff3f0a; box-shadow: 0 6px 18px rgba(255, 63, 10, 0.2);" id="confirmDeleteBtn" onclick="executeDelete()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; display: inline-block; vertical-align: middle;">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span style="vertical-align: middle;">Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         MODAL: Profil Saya (Premium Style)
         ═══════════════════════════════════════════════════════ --}}
    <div id="profileModal" class="hidden premium-modal-overlay">
        <div class="premium-modal-shell">
            <div class="premium-modal-card" style="max-width: 440px;">
                <button type="button" onclick="closeProfileModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                
                <h2 class="premium-modal-title" style="margin-bottom: 24px;">Profil Saya</h2>
                
                <div class="profile-avatar-section" style="text-align: center; margin-bottom: 24px;">
                    <div style="width: 76px; height: 76px; margin: 0 auto 14px; border-radius: 50%; background: linear-gradient(135deg, #080d5f, #0f179e); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(8, 13, 95, 0.15);">
                        <span style="font-size: 26px; font-weight: 700; color: white; text-transform: uppercase;">
                            {{ substr(auth('pengguna')->user()->nama_user ?? 'P', 0, 1) }}
                        </span>
                    </div>
                    <h3 style="font-size: 16.5px; font-weight: 700; color: #1e2243; margin-bottom: 4px;">
                        {{ auth('pengguna')->user()->nama_user ?? '-' }}
                    </h3>
                    <span style="display: inline-block; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; background: #eef1f8; color: #080d5f; text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ auth('pengguna')->user()->jabatan ?? 'Pengguna' }}
                    </span>
                </div>

                <div class="profile-info-grid" style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 28px; background: #f8fafc; border-radius: 14px; padding: 18px; border: 1px solid rgba(8, 13, 95, 0.04);">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed rgba(8, 13, 95, 0.08); padding-bottom: 10px;">
                        <span style="font-size: 13px; font-weight: 600; color: #8a8fa5;">Nomor Induk (NIK)</span>
                        <span style="font-size: 13px; font-weight: 700; color: #1e2243;">
                            {{ auth('pengguna')->user()->nik ?? '-' }}
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed rgba(8, 13, 95, 0.08); padding-bottom: 10px;">
                        <span style="font-size: 13px; font-weight: 600; color: #8a8fa5;">Alamat Email</span>
                        <span style="font-size: 13px; font-weight: 700; color: #1e2243;">
                            {{ auth('pengguna')->user()->email ?? '-' }}
                        </span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; font-weight: 600; color: #8a8fa5;">Status Akun</span>
                        <span style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #10b981;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                            Aktif
                        </span>
                    </div>
                </div>
                
                <div class="premium-modal-actions" style="margin-top: 0; display: flex; gap: 12px;">
                    <button type="button" onclick="closeProfileModal()" class="flex-1 h-11 rounded-xl border border-gray-200 bg-white cursor-pointer font-semibold text-[#1e2243] hover:bg-gray-50 transition-all duration-200" style="font-size: 14px;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* ── Profile Modal ────────────────────────────────────── */
        function openProfileModal() {
            const m = document.getElementById('profileModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
            
            // Close profile panel
            const profilePanel = document.querySelector('[data-profile-panel]');
            if (profilePanel) {
                profilePanel.setAttribute('hidden', '');
            }
        }

        function closeProfileModal() {
            const m = document.getElementById('profileModal');
            if (!m) return;
            m.classList.add('closing');
            setTimeout(() => {
                m.classList.add('hidden');
                m.classList.remove('flex', 'closing');
            }, 300);
        }

        /* ── Confirm Delete Modal ─────────────────────────────── */
        let _pendingDeleteForm = null;

        function confirmDelete(formElement, message) {
            _pendingDeleteForm = formElement;
            const modal = document.getElementById('confirmDeleteModal');
            const msgEl = document.getElementById('confirmDeleteMessage');
            msgEl.textContent = message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmDeleteModal');
            if (!modal) return;
            modal.classList.add('closing');
            setTimeout(() => {
                _pendingDeleteForm = null;
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'closing');
            }, 300);
        }

        function executeDelete() {
            if (_pendingDeleteForm) {
                _pendingDeleteForm.submit();
            }
            closeConfirmModal();
        }

        /* ── Premium Toast Notification Utility ────────────────── */
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `premium-toast ${type}`;
            
            let icon = '';
            if (type === 'success') {
                icon = `
                    <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                `;
            } else if (type === 'error') {
                icon = `
                    <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                `;
            } else {
                icon = `
                    <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                `;
            }

            toast.innerHTML = `
                <div class="toast-content">
                    <div class="toast-icon-wrap">${icon}</div>
                    <div class="toast-message">${message}</div>
                    <button type="button" class="toast-close-btn" onclick="this.parentElement.parentElement.remove()" aria-label="Tutup">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="toast-progress-bar"></div>
            `;

            container.appendChild(toast);

            // Auto dismiss after 4 seconds
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 4000);
        };
    </script>
</body>
</html>
