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
            storeUserLinkRoute: "{{ route('pengguna.links.store') }}",
            storeCategoryRoute: "{{ route('pengguna.categories.store') }}",
            updateCategoryRoute: "{{ route('pengguna.categories.update', ['id' => '__ID__']) }}",
            deleteCategoryRoute: "{{ route('pengguna.categories.destroy', ['id' => '__ID__']) }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
</head>
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
                        <button type="button" class="profile-icon" aria-label="Profil pengguna" aria-expanded="false" data-profile-toggle style="padding: 0; overflow: hidden; display: grid; place-items: center;">
                            @if(auth('pengguna')->user()->foto)
                                <img src="{{ asset(auth('pengguna')->user()->foto) }}" style="width: 100% !important; height: 100% !important; object-fit: cover !important; border-radius: 50%;">
                            @else
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" style="width: 20px; height: 20px; color: var(--orange);">
                                    <path d="M12 12.2a4.4 4.4 0 1 0 0-8.8 4.4 4.4 0 0 0 0 8.8Zm-7 8.4c.9-3.5 3.5-5.4 7-5.4s6.1 1.9 7 5.4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            @endif
                        </button>

                        <div class="profile-panel" data-profile-panel hidden style="right: 0; left: auto;">
                            <div class="profile-panel-header">
                                <div class="profile-panel-avatar">
                                    @if(auth('pengguna')->user()->foto)
                                        <img src="{{ asset(auth('pengguna')->user()->foto) }}" alt="Avatar" style="width: 100% !important; height: 100% !important; object-fit: cover !important; border-radius: 50%;">
                                    @else
                                        <span style="font-weight: 700;">{{ strtoupper(substr(auth('pengguna')->user()->nama_user ?? 'P', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="profile-panel-info">
                                    <div class="profile-panel-name">{{ auth('pengguna')->user()->nama_user }}</div>
                                    <div class="profile-panel-role">{{ auth('pengguna')->user()->jabatan ?? 'Pengguna' }}</div>
                                </div>
                            </div>
                            <hr class="profile-panel-divider">
                            <div class="profile-panel-actions">
                                <button type="button" class="profile-panel-btn" onclick="openProfileModal()">
                                    <div class="btn-icon-wrap">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                                        </svg>
                                    </div>
                                    <span>Profil Saya</span>
                                </button>

                                <button type="button" class="profile-panel-btn" onclick="openPasswordModal()">
                                    <div class="btn-icon-wrap">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </div>
                                    <span>Ubah Kata Sandi</span>
                                </button>
                                
                                <hr class="profile-panel-divider">

                                <form action="{{ route('logout') }}" method="POST" class="profile-panel-form">
                                    @csrf
                                    <button type="submit" class="profile-panel-btn logout-btn">
                                        <div class="btn-icon-wrap">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4m7 14l5-5-5-5m5 5H9" />
                                            </svg>
                                        </div>
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
                        <h1>Selamat Datang, <span class="hero-name">{{ auth('pengguna')->user()->nama_user ?? 'Pengguna' }}</span></h1>
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
                            <div class="premium-dashed-empty">
                                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                </svg>
                                <div class="empty-title">Belum ada link pribadi ditambahkan</div>
                                <div class="empty-desc">Tautan website kustom buatan Anda akan muncul di sini. Silakan klik tombol "+ Link" di atas untuk menambahkan.</div>
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
                            <div class="folder-card" data-category-folder="{{ $category->nama_kategori }}" data-category-id="{{ $category->id_kategori }}" data-category-nik="{{ $category->nik ?? '' }}">
                                {{-- Tombol edit folder kategori (hanya untuk kategori milik pengguna) --}}
                                @if($category->nik === auth('pengguna')->user()->nik)
                                <button type="button" class="folder-edit-btn" data-category-edit-toggle="{{ $category->nama_kategori }}" data-category-db-id="{{ $category->id_kategori }}" aria-label="Edit Kategori">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"></path>
                                    </svg>
                                </button>
                                @endif
                                <div class="folder-header">
                                    <div class="category-folder-icon-wrapper" style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 12px; background: rgba(8, 13, 95, 0.04); color: #080d5f; transition: all 0.2s;">
                                        @if($category->icon && array_key_exists($category->icon, $iconPaths))
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                {!! $iconPaths[$category->icon] !!}
                                            </svg>
                                        @else
                                            <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo" style="width: 32px; height: 32px; object-fit: contain;">
                                        @endif
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
        CATEGORY BUILDER MODAL (Premium Style)
    ═══════════════════════════════════════════════════════ --}}
    <div class="hidden premium-modal-overlay" data-category-builder-modal>
        <div class="premium-modal-shell" style="max-width: 500px;">
            <div class="premium-modal-card" role="dialog" aria-modal="true" aria-labelledby="category-builder-title-label">
                <button type="button" class="premium-modal-close-btn" data-category-builder-close aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <h2 id="category-builder-title-label" class="premium-modal-title" data-category-builder-modal-title>Tambah Kategori</h2>

                <div class="premium-modal-form-group">
                    <label class="premium-modal-label">Nama Kategori</label>
                    <input type="text" class="premium-modal-input" placeholder="Masukkan nama kategori.." data-category-builder-title>
                </div>

                <div class="premium-modal-form-group">
                    <label class="premium-modal-label">Pilih Ikon</label>
                    <div class="category-builder-icon-grid" id="user-builder-icon-picker">
                        <label class="cb-icon-option is-active" title="Default (Polibatam)">
                            <input type="radio" name="builder_icon" value="" checked onchange="selectUserBuilderIcon(this)">
                            <img src="{{ asset('images/logo-polibatam.png') }}" alt="Default" style="width: 20px; height: 20px; object-fit: contain;">
                        </label>
                        @foreach(['home', 'grid', 'sparkles', 'user', 'chain', 'folder', 'tag', 'book', 'globe', 'settings', 'briefcase', 'heart'] as $iconName)
                            <label class="cb-icon-option" data-icon-value="{{ $iconName }}" title="{{ $iconName }}">
                                <input type="radio" name="builder_icon" value="{{ $iconName }}" onchange="selectUserBuilderIcon(this)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $iconPaths[$iconName] !!}
                                </svg>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="premium-modal-form-group" style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label class="premium-modal-label" style="margin-bottom: 0;">Link Terkait</label>
                        <button type="button" class="cb-add-link-btn" data-category-builder-link-add>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah Link
                        </button>
                    </div>
                    <div class="cb-links-container" data-category-builder-links></div>
                    <p class="cb-empty-state" data-category-builder-empty>Belum ada link ditambahkan</p>
                </div>

                <div class="premium-modal-actions">
                    <button type="button" class="premium-modal-btn btn-cancel" data-category-builder-close>Batal</button>
                    <button type="button" class="premium-modal-btn btn-save" data-category-builder-save>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan Kategori
                    </button>
                </div>

                {{-- Delete button (only visible in edit mode) --}}
                <div class="cb-delete-section" data-category-builder-delete-section style="display: none;">
                    <button type="button" class="cb-delete-btn" data-category-builder-reset>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Kategori
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                        <input type="text" name="url" id="linkUrl" placeholder="https://example.com" required class="premium-modal-input">
                    </div>

                    <div class="premium-modal-form-group">
                        <div class="flex justify-between items-center mb-2">
                            <label class="premium-modal-label" style="margin-bottom: 0;">Kategori</label>
                            <button type="button" onclick="toggleUserQuickCategoryForm()" class="cb-add-link-btn" style="background: none; border: none; padding: 0; color: var(--orange);">
                                + Tambah Kategori Baru
                            </button>
                        </div>
                        <select name="id_kategori" id="linkKategori" class="premium-modal-input appearance-none cursor-pointer">
                            <option value="">Pilih Kategori...</option>
                            @foreach($categoriesList as $cat)
                                <option value="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>

                        {{-- Quick Add Category Section (Initially Hidden) --}}
                        <div id="userQuickCategoryContainer" class="hidden" style="display: none; margin-top: 12px; padding: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; flex-direction: column; gap: 8px;">
                            <span style="font-size: 11.5px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Tambah Kategori Baru</span>
                            
                            {{-- Quick Add Icon Picker --}}
                            <div style="margin-bottom: 6px;">
                                <span style="display: block; font-size: 10px; font-weight: 700; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Pilih Ikon:</span>
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;" id="user-quick-icon-picker">
                                    <label class="user-quick-icon-option" style="cursor: pointer; display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; border: 1.5px solid transparent; background: #ffffff; transition: all 0.2s;" title="Default (Polibatam)">
                                        <input type="radio" name="quick_icon" value="" checked style="display: none;" onchange="selectUserQuickIcon(this)">
                                        <img src="{{ asset('images/logo-polibatam.png') }}" alt="Default" style="width: 16px; height: 16px; object-fit: contain;">
                                    </label>
                                    @foreach(['home', 'grid', 'sparkles', 'user', 'chain', 'folder', 'tag', 'book', 'globe', 'settings', 'briefcase', 'heart'] as $iconName)
                                        <label class="user-quick-icon-option" data-icon-value="{{ $iconName }}" style="cursor: pointer; display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; border: 1.5px solid transparent; background: #ffffff; transition: all 0.2s; color: #475569;" title="{{ $iconName }}">
                                            <input type="radio" name="quick_icon" value="{{ $iconName }}" style="display: none;" onchange="selectUserQuickIcon(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                {!! $iconPaths[$iconName] !!}
                                            </svg>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div style="display: flex; gap: 8px; align-items: center; width: 100%;">
                                <input type="text" id="user_quick_nama_kategori" placeholder="Nama Kategori..." class="premium-modal-input" style="height: 38px; min-height: 0; padding: 0 12px; font-size: 13px; flex: 1; border-radius: 8px; background: #ffffff;">
                                <button type="button" onclick="submitUserQuickCategory()" style="height: 38px; padding: 0 16px; border-radius: 8px; background: var(--orange, #f97316); color: white; font-size: 12px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.opacity=0.9" onmouseout="this.style.opacity=1">
                                    Simpan
                                </button>
                            </div>
                        </div>
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
            <div class="premium-modal-card" style="max-width: 440px; padding: 28px 24px 20px;">
                <button type="button" onclick="closeProfileModal()" class="premium-modal-close-btn" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                
                <h2 class="premium-modal-title" style="margin-bottom: 20px;">Profil Saya</h2>
                
                <form id="profileForm" action="{{ route('pengguna.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="profile-avatar-section" style="text-align: center; margin-bottom: 20px;">
                        <div class="profile-avatar-wrapper" onclick="triggerProfilePhotoUpload()" style="position: relative; width: 80px; height: 80px; margin: 0 auto 12px; cursor: default; transition: all 0.2s ease;">
                            <div id="profileAvatarCircle" style="width: 100%; height: 100%; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #080d5f, #0f179e); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(8, 13, 95, 0.15); border: 2px solid #fff;">
                                @if(auth('pengguna')->user()->foto)
                                    <img id="profileAvatarImg" src="{{ asset(auth('pengguna')->user()->foto) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span id="profileAvatarInitials" style="font-size: 28px; font-weight: 700; color: white; text-transform: uppercase;">
                                        {{ substr(auth('pengguna')->user()->nama_user ?? 'P', 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            {{-- Camera Edit Overlay --}}
                            <div class="avatar-edit-overlay" style="display: none; position: absolute; inset: 0; background: rgba(0, 0, 0, 0.4); border-radius: 50%; align-items: center; justify-content: center; color: #fff; font-size: 11px; font-weight: bold; pointer-events: none;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                            </div>
                            <input type="file" name="foto" id="profilePhotoInput" accept="image/*" style="display: none;" onchange="previewProfilePhoto(this)">
                        </div>

                        <h3 class="profile-view-only" style="font-size: 16.5px; font-weight: 700; color: #1e2243; margin-bottom: 4px;">
                            {{ auth('pengguna')->user()->nama_user ?? '-' }}
                        </h3>
                        
                        <div class="profile-edit-only" style="display: none; flex-direction: column; align-items: stretch; width: 100%; max-width: 280px; margin: 0 auto 10px;">
                            <label class="premium-modal-label" style="text-align: left; font-size: 11px; margin-bottom: 4px;">Nama Lengkap</label>
                            <input type="text" name="nama_user" value="{{ auth('pengguna')->user()->nama_user }}" required class="premium-modal-input" style="width: 100%; text-align: center; font-weight: 700; font-size: 14.5px; height: 38px; border-radius: 10px;">
                        </div>

                        <span style="display: inline-block; font-size: 10.5px; font-weight: 700; padding: 4px 12px; border-radius: 20px; background: #eef1f8; color: #080d5f; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ auth('pengguna')->user()->jabatan ?? 'Pengguna' }}
                        </span>
                    </div>

                    <div class="profile-info-grid" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; background: #f8fafc; border-radius: 14px; padding: 16px; border: 1px solid rgba(8, 13, 95, 0.04);">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed rgba(8, 13, 95, 0.08); padding-bottom: 8px;">
                            <span style="font-size: 12.5px; font-weight: 600; color: #8a8fa5;">Nomor Induk (NIK)</span>
                            <span style="font-size: 12.5px; font-weight: 700; color: #1e2243;">
                                {{ auth('pengguna')->user()->nik ?? '-' }}
                            </span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed rgba(8, 13, 95, 0.08); padding-bottom: 8px;">
                            <span style="font-size: 12.5px; font-weight: 600; color: #8a8fa5; display: flex; align-items: center; gap: 4px;">
                                Alamat Email 
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="opacity-50" style="vertical-align: middle;" title="Email tidak dapat diubah">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <span style="font-size: 12.5px; font-weight: 700; color: #8a8fa5;">
                                {{ auth('pengguna')->user()->email ?? '-' }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 12.5px; font-weight: 600; color: #8a8fa5;">Status Akun</span>
                            <span style="display: flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 700; color: #10b981;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                                Aktif
                            </span>
                        </div>
                    </div>
                    
                    <div class="premium-modal-actions" style="margin-top: 0; display: flex; gap: 12px; width: 100%;">
                        <div class="profile-view-only" style="display: flex; gap: 12px; width: 100%;">
                            <button type="button" onclick="toggleProfileEditMode(true)" class="flex-1 h-11 rounded-xl bg-[#080d5f] cursor-pointer font-semibold text-white hover:bg-[#0c148f] transition-all duration-200" style="font-size: 14px; border: none;">
                                Edit Profil
                            </button>
                            <button type="button" onclick="closeProfileModal()" class="flex-1 h-11 rounded-xl border border-gray-200 bg-white cursor-pointer font-semibold text-[#1e2243] hover:bg-gray-50 transition-all duration-200" style="font-size: 14px;">
                                Tutup
                            </button>
                        </div>
                        <div class="profile-edit-only" style="display: none; gap: 12px; width: 100%;">
                            <button type="submit" class="flex-1 h-11 rounded-xl bg-[#10b981] cursor-pointer font-semibold text-white hover:bg-[#0d9668] transition-all duration-200" style="font-size: 14px; border: none;">
                                Simpan
                            </button>
                            <button type="button" onclick="toggleProfileEditMode(false)" class="flex-1 h-11 rounded-xl border border-gray-200 bg-white cursor-pointer font-semibold text-[#1e2243] hover:bg-gray-50 transition-all duration-200" style="font-size: 14px;">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
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
                toggleProfileEditMode(false); // Reset on close
            }, 300);
        }

        function toggleProfileEditMode(isEdit) {
            const modal = document.getElementById('profileModal');
            const viewGroup = modal.querySelectorAll('.profile-view-only');
            const editGroup = modal.querySelectorAll('.profile-edit-only');
            const overlay = modal.querySelector('.avatar-edit-overlay');
            const wrapper = modal.querySelector('.profile-avatar-wrapper');

            if (isEdit) {
                viewGroup.forEach(el => el.style.setProperty('display', 'none', 'important'));
                editGroup.forEach(el => el.style.setProperty('display', 'flex', 'important'));
                if (overlay) overlay.style.display = 'flex';
                if (wrapper) wrapper.style.cursor = 'pointer';
            } else {
                viewGroup.forEach(el => el.style.setProperty('display', '', ''));
                editGroup.forEach(el => el.style.setProperty('display', 'none', 'important'));
                if (overlay) overlay.style.display = 'none';
                if (wrapper) wrapper.style.cursor = 'default';
                
                // Reset form
                document.getElementById('profileForm').reset();
                
                // Restore original preview
                const originalFoto = "{{ auth('pengguna')->user()->foto ? asset(auth('pengguna')->user()->foto) : '' }}";
                const originalInitials = "{{ substr(auth('pengguna')->user()->nama_user ?? 'P', 0, 1) }}";
                const circle = document.getElementById('profileAvatarCircle');
                if (originalFoto) {
                    circle.innerHTML = `<img id="profileAvatarImg" src="${originalFoto}" style="width: 100%; height: 100%; object-fit: cover;">`;
                } else {
                    circle.innerHTML = `<span id="profileAvatarInitials" style="font-size: 28px; font-weight: 700; color: white; text-transform: uppercase;">${originalInitials}</span>`;
                }
            }
        }

        function triggerProfilePhotoUpload() {
            const modal = document.getElementById('profileModal');
            const editGroup = modal.querySelector('.profile-edit-only');
            // Only trigger in edit mode
            if (editGroup && editGroup.style.display !== 'none') {
                document.getElementById('profilePhotoInput').click();
            }
        }

        function previewProfilePhoto(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const circle = document.getElementById('profileAvatarCircle');
                    circle.innerHTML = `<img id="profileAvatarImg" src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                };
                reader.readAsDataURL(file);
            }
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

        /* ── Quick Add Icon Picker Helpers ──────────────────── */
        function selectUserQuickIcon(radio) {
            document.querySelectorAll('.user-quick-icon-option').forEach(function(lbl) {
                lbl.style.borderColor = 'transparent';
                lbl.style.background = '#ffffff';
                lbl.style.color = '#475569';
            });
            var parent = radio.parentElement;
            parent.style.borderColor = '#080d5f';
            parent.style.background = 'rgba(8, 13, 95, 0.05)';
            parent.style.color = '#080d5f';
        }

        function resetQuickIconPicker() {
            var defaultRadio = document.querySelector('input[name="quick_icon"][value=""]');
            if (defaultRadio) {
                defaultRadio.checked = true;
                selectUserQuickIcon(defaultRadio);
            }
        }

        function toggleUserQuickCategoryForm() {
            const container = document.getElementById('userQuickCategoryContainer');
            if (container) {
                if (container.style.display === 'none' || container.classList.contains('hidden')) {
                    container.classList.remove('hidden');
                    container.style.display = 'flex';
                    resetQuickIconPicker();
                    document.getElementById('user_quick_nama_kategori').focus();
                } else {
                    container.style.display = 'none';
                }
            }
        }

        function submitUserQuickCategory() {
            const nameInput = document.getElementById('user_quick_nama_kategori');
            const name = nameInput ? nameInput.value.trim() : '';

            if (!name) {
                showToast('Nama kategori tidak boleh kosong.', 'error');
                return;
            }

            // Get the selected icon
            const selectedIcon = document.querySelector('input[name="quick_icon"]:checked');
            const iconValue = selectedIcon ? selectedIcon.value : '';

            fetch("{{ route('pengguna.categories.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    nama_kategori: name,
                    icon: iconValue
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
                    const select = document.getElementById('linkKategori');
                    if (select) {
                        const opt = document.createElement('option');
                        opt.value = data.category.id_kategori;
                        opt.textContent = data.category.nama_kategori;
                        select.appendChild(opt);
                        select.value = data.category.id_kategori;
                        if (typeof select.refreshPremiumSelect === 'function') {
                            select.refreshPremiumSelect();
                        }
                    }

                    // Reset input, icon picker & hide
                    nameInput.value = '';
                    resetQuickIconPicker();
                    const container = document.getElementById('userQuickCategoryContainer');
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
    </script>
</body>
</html>
