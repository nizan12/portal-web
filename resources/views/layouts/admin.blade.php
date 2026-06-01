{{--
|--------------------------------------------------------------------------
| Layout Admin – POLTREE
|--------------------------------------------------------------------------
| Layout utama untuk semua halaman admin dashboard.
| Berisi: sidebar navigasi, topbar, profil panel, password modal.
|
| CSS → resources/css/admin.css (custom styles)
| CSS → resources/css/app.css (Tailwind base)
| JS  → inline <script> di bawah (profil toggle, password modal)
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? ('Admin - '.config('app.name', 'POLTREE')) }}</title>
    <meta name="description" content="Dashboard admin POLTREE untuk mengelola layanan, pengguna, dan kategori.">

    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite: Tailwind + Admin CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>
    {{-- ═══════════════════════════════════════════════════════
         Icon paths & asset resolver (dipakai sidebar & topbar)
         ═══════════════════════════════════════════════════════ --}}
    @php
        $iconPaths = [
            'home' => '<path d="M5 10.7 12 5l7 5.7V19a1 1 0 0 1-1 1h-4.1v-5h-3.8v5H6a1 1 0 0 1-1-1v-8.3Z" fill="currentColor" />',
            'sparkles' => '<path d="M12 3.2 13.1 7l3.7 1.1-3.7 1.1L12 13l-1.1-3.8-3.7-1.1L10.9 7 12 3.2Zm6.2 7.8.7 1.8 1.9.7-1.9.7-.7 1.8-.7-1.8-1.9-.7 1.9-.7.7-1.8ZM5.1 11.8l.8 2.3 2.3.8-2.3.8-.8 2.3-.8-2.3-2.3-.8 2.3-.8.8-2.3Z" fill="currentColor" />',
            'user' => '<path d="M12 11.4a3.7 3.7 0 1 0 0-7.4 3.7 3.7 0 0 0 0 7.4Zm0 1.9c-4 0-7.3 2.1-7.3 4.7V20h14.6v-2.1c0-2.6-3.3-4.6-7.3-4.6Z" fill="currentColor" />',
            'chain' => '<path d="M8.4 15.6a3 3 0 0 1 0-4.2l2-2a3 3 0 1 1 4.2 4.2l-.7.7h-1.9l1.2-1.2a1.4 1.4 0 1 0-2-2l-2 2a1.4 1.4 0 1 0 2 2l.6-.6h1.9l-1.3 1.3a3 3 0 0 1-4.2 0Zm7.2-7.2a3 3 0 0 1 0 4.2l-2 2a3 3 0 1 1-4.2-4.2l.7-.7H12l-1.2 1.2a1.4 1.4 0 1 0 2 2l2-2a1.4 1.4 0 1 0-2-2l-.6.6h-1.9l1.3-1.3a3 3 0 0 1 4.2 0Z" fill="currentColor" />',
            'folder' => '<path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z" fill="currentColor" />',
            'profile' => '<path d="M12 11.6a3.8 3.8 0 1 0 0-7.6 3.8 3.8 0 0 0 0 7.6Zm0 1.9c-4.1 0-7.4 2-7.4 4.5V20h14.8v-2c0-2.5-3.3-4.5-7.4-4.5Z" fill="currentColor" />',
            'tag' => '<path d="M3.3 3.3v7.1L13 20.1l7.1-7.1L10.4 3.3H3.3Zm4.2 2.1a2.1 2.1 0 1 1 0 4.2 2.1 2.1 0 0 1 0-4.2Z" fill="currentColor" />',
            'default' => '<circle cx="12" cy="12" r="8" fill="currentColor" />',
        ];

        $iconAssets = [
            'home' => 'icons/admin/home.svg',
            'sparkles' => 'icons/admin/services.svg',
            'user' => 'icons/admin/user.svg',
            'chain' => 'icons/admin/links.svg',
            'folder' => 'icons/admin/categories.svg',
            'profile' => 'icons/admin/profile.svg',
            'tag' => 'icons/admin/tag.svg',
        ];

        $resolveIconAsset = static function (string $key) use ($iconAssets): ?string {
            $path = $iconAssets[$key] ?? null;
            if (! $path) return null;
            return file_exists(public_path($path)) ? asset($path) : null;
        };
    @endphp

    <div class="admin-shell">
        {{-- ═══════════════════════════════════════════════════
             SIDEBAR: Logo + Navigasi admin
             ═══════════════════════════════════════════════════ --}}
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <span class="admin-brand-dot" aria-hidden="true"></span>
                <div class="admin-brand-text">
                    <strong>Pol</strong><span>Tree</span>
                </div>
            </div>

            <nav class="admin-nav" aria-label="Menu admin">
                @foreach ($menuItems as $item)
                    @php $menuIconAsset = $resolveIconAsset($item['icon']); @endphp
                    <a
                        href="{{ $item['href'] }}"
                        class="admin-nav-link"
                        @if ($item['active']) aria-current="page" @endif
                        @if ($item['href'] === '#') aria-disabled="true" onclick="return false;" @endif
                    >
                        @if ($menuIconAsset)
                            <img src="{{ $menuIconAsset }}" alt="" aria-hidden="true">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                {!! $iconPaths[$item['icon']] ?? $iconPaths['default'] !!}
                            </svg>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="admin-main">
            {{-- ═══════════════════════════════════════════════
                 TOPBAR: Judul halaman + Profil
                 ═══════════════════════════════════════════════ --}}
            <header class="admin-topbar">
                <div class="admin-topbar-title">{{ $topbarTitle ?? 'Dashboard' }}</div>

                @php $profileIconAsset = $resolveIconAsset('profile'); @endphp
                <div class="profile-menu-wrap" style="display: flex; align-items: center; gap: 16px;">
                    <span style="font-size: 13.5px; font-weight: 600; color: #1e2243; font-family: 'Poppins', sans-serif; margin-right: 8px;">
                        {{ auth('admin')->user()->nama }}
                    </span>
                    <button type="button" class="admin-profile-button" aria-label="Profil admin" data-profile-toggle style="padding: 0; overflow: hidden; display: grid; place-items: center;">
                        @if(auth('admin')->user()->foto)
                            <img src="{{ asset(auth('admin')->user()->foto) }}" style="width: 100% !important; height: 100% !important; object-fit: cover !important; border-radius: 50%;">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="width: 20px; height: 20px; color: var(--navy);">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                            </svg>
                        @endif
                    </button>

                    {{-- Panel profil dropdown --}}
                    <div class="profile-panel" data-profile-panel hidden style="right: 0; left: auto;">
                        <div class="profile-panel-header">
                            <div class="profile-panel-avatar">
                                @if(auth('admin')->user()->foto)
                                    <img src="{{ asset(auth('admin')->user()->foto) }}" alt="Avatar" style="width: 100% !important; height: 100% !important; object-fit: cover !important; border-radius: 50%;">
                                @else
                                    <span style="font-weight: 700;">{{ strtoupper(substr(auth('admin')->user()->nama, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="profile-panel-info">
                                <div class="profile-panel-name">{{ auth('admin')->user()->nama }}</div>
                                <div class="profile-panel-role">Administrator</div>
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
            </header>

            {{-- ═══════════════════════════════════════════════
                 MODAL: Ubah Kata Sandi Admin
                 ═══════════════════════════════════════════════ --}}
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

                        <form action="{{ route('admin.password.update') }}" method="POST">
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

            {{-- Konten halaman admin --}}
            <main class="admin-content">
                @yield('content')
            </main>
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
                
                <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="profile-avatar-section" style="text-align: center; margin-bottom: 20px;">
                        <div class="profile-avatar-wrapper" onclick="triggerProfilePhotoUpload()" style="position: relative; width: 80px; height: 80px; margin: 0 auto 12px; cursor: default; transition: all 0.2s ease;">
                            <div id="profileAvatarCircle" style="width: 100%; height: 100%; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #080d5f, #0f179e); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(8, 13, 95, 0.15); border: 2px solid #fff;">
                                @if(auth('admin')->user()->foto)
                                    <img id="profileAvatarImg" src="{{ asset(auth('admin')->user()->foto) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span id="profileAvatarInitials" style="font-size: 28px; font-weight: 700; color: white; text-transform: uppercase;">
                                        {{ substr(auth('admin')->user()->nama ?? 'A', 0, 1) }}
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
                            {{ auth('admin')->user()->nama ?? '-' }}
                        </h3>
                        
                        <div class="profile-edit-only" style="display: none; flex-direction: column; align-items: stretch; width: 100%; max-width: 280px; margin: 0 auto 10px;">
                            <label class="premium-modal-label" style="text-align: left; font-size: 11px; margin-bottom: 4px;">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ auth('admin')->user()->nama }}" required class="premium-modal-input" style="width: 100%; text-align: center; font-weight: 700; font-size: 14.5px; height: 38px; border-radius: 10px;">
                        </div>

                        <span style="display: inline-block; font-size: 10.5px; font-weight: 700; padding: 4px 12px; border-radius: 20px; background: #eef1f8; color: #080d5f; text-transform: uppercase; letter-spacing: 0.5px;">
                            Administrator
                        </span>
                    </div>

                    <div class="profile-info-grid" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; background: #f8fafc; border-radius: 14px; padding: 16px; border: 1px solid rgba(8, 13, 95, 0.04);">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed rgba(8, 13, 95, 0.08); padding-bottom: 8px;">
                            <span style="font-size: 12.5px; font-weight: 600; color: #8a8fa5;">Nomor Induk (NIK)</span>
                            <span style="font-size: 12.5px; font-weight: 700; color: #1e2243;">
                                {{ auth('admin')->user()->nik_admin ?? '-' }}
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
                                {{ auth('admin')->user()->email ?? '-' }}
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

    {{-- ═══════════════════════════════════════════════════════
         SCRIPT: Profil toggle & Password modal
         ═══════════════════════════════════════════════════════ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profileToggle = document.querySelector('[data-profile-toggle]');
            const profilePanel = document.querySelector('[data-profile-panel]');

            if (profileToggle && profilePanel) {
                profileToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isHidden = profilePanel.hasAttribute('hidden');
                    if (isHidden) {
                        profilePanel.removeAttribute('hidden');
                    } else {
                        profilePanel.setAttribute('hidden', '');
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!profilePanel.hasAttribute('hidden') && !profilePanel.contains(e.target) && e.target !== profileToggle) {
                        profilePanel.setAttribute('hidden', '');
                    }
                });
            }
        });

        function openProfileModal() {
            const m = document.getElementById('profileModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
            
            // Close topbar profile panel
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
                toggleProfileEditMode(false); // Reset to view mode on close
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
                const originalFoto = "{{ auth('admin')->user()->foto ? asset(auth('admin')->user()->foto) : '' }}";
                const originalInitials = "{{ substr(auth('admin')->user()->nama ?? 'A', 0, 1) }}";
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

        function openPasswordModal() {
            const m = document.getElementById('passwordModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closePasswordModal() {
            const m = document.getElementById('passwordModal');
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

        // ── Premium Custom Select ──
        window.initPremiumSelect = function (selectEl) {
            if (!selectEl || selectEl.dataset.premiumSelectInitialized) {
                return;
            }

            // Hide original select
            selectEl.style.display = 'none';

            // Create wrapper
            const wrapper = document.createElement('div');
            wrapper.className = 'premium-select-wrapper';

            // Insert wrapper before selectEl
            selectEl.parentNode.insertBefore(wrapper, selectEl);
            wrapper.appendChild(selectEl); // move selectEl inside wrapper

            // Create trigger
            const trigger = document.createElement('div');
            trigger.className = 'premium-select-trigger';
            trigger.setAttribute('tabindex', '0');

            const triggerText = document.createElement('span');
            triggerText.className = 'trigger-text';
            
            const currentOption = selectEl.options[selectEl.selectedIndex];
            triggerText.textContent = currentOption ? currentOption.textContent : 'Pilih...';

            const triggerArrow = document.createElement('span');
            triggerArrow.className = 'trigger-arrow';
            triggerArrow.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px; height:14px;"><polyline points="6 9 12 15 18 9"></polyline></svg>`;

            trigger.appendChild(triggerText);
            trigger.appendChild(triggerArrow);
            wrapper.appendChild(trigger);

            // Create options container
            const optionsContainer = document.createElement('div');
            optionsContainer.className = 'premium-select-options';
            wrapper.appendChild(optionsContainer);

            // Build option items function
            const buildOptions = function () {
                optionsContainer.innerHTML = '';
                Array.from(selectEl.options).forEach(function (opt, idx) {
                    const optEl = document.createElement('div');
                    optEl.className = 'premium-select-option';
                    if (opt.selected) {
                        optEl.classList.add('is-selected');
                    }
                    optEl.dataset.value = opt.value;
                    optEl.dataset.index = idx;
                    
                    const optText = document.createElement('span');
                    optText.textContent = opt.textContent;
                    
                    const optCheck = document.createElement('span');
                    optCheck.className = 'option-check';
                    optCheck.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px; height:12px;"><polyline points="20 6 9 17 4 12"></polyline></svg>`;

                    optEl.appendChild(optText);
                    optEl.appendChild(optCheck);

                    optEl.addEventListener('click', function (e) {
                        e.stopPropagation();
                        selectEl.selectedIndex = idx;
                        triggerText.textContent = opt.textContent;
                        
                        // Trigger change event on original select
                        const event = new Event('change', { bubbles: true });
                        selectEl.dispatchEvent(event);
                        
                        closeDropdown();
                    });

                    optionsContainer.appendChild(optEl);
                });
            };

            const toggleDropdown = function () {
                const isOpen = optionsContainer.classList.contains('is-open');
                if (isOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            };

            const openDropdown = function () {
                // Close all other open premium selects first
                document.querySelectorAll('.premium-select-options.is-open').forEach(function (el) {
                    if (el !== optionsContainer) {
                        el.classList.remove('is-open');
                        el.previousElementSibling.classList.remove('is-active');
                        // Restore parent scroll container's overflow if needed
                        const parentScroll = el.closest('.premium-modal-scroll-container');
                        if (parentScroll) {
                            parentScroll.style.setProperty('overflow-y', 'auto', 'important');
                        }
                    }
                });

                buildOptions(); // Rebuild options to reflect current selection/state
                optionsContainer.classList.add('is-open');
                trigger.classList.add('is-active');

                // Prevent clipping by parent scroll containers
                const parentScroll = wrapper.closest('.premium-modal-scroll-container');
                if (parentScroll) {
                    parentScroll.style.setProperty('overflow-y', 'visible', 'important');
                }
            };

            const closeDropdown = function () {
                optionsContainer.classList.remove('is-open');
                trigger.classList.remove('is-active');

                // Restore parent scroll container's overflow
                const parentScroll = wrapper.closest('.premium-modal-scroll-container');
                if (parentScroll) {
                    parentScroll.style.setProperty('overflow-y', 'auto', 'important');
                }
            };

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleDropdown();
            });

            // Focus and keyboard navigation
            trigger.addEventListener('keydown', function (e) {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    toggleDropdown();
                } else if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            // Close on click outside
            document.addEventListener('click', function (e) {
                if (!wrapper.contains(e.target)) {
                    closeDropdown();
                }
            });

            // Mark as initialized
            selectEl.dataset.premiumSelectInitialized = 'true';

            // Add reference on original select element so we can manually trigger update/refresh
            selectEl.refreshPremiumSelect = function() {
                const opt = selectEl.options[selectEl.selectedIndex];
                triggerText.textContent = opt ? opt.textContent : 'Pilih...';
            };
        };

        // Automatically initialize all custom selects on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('select.premium-modal-input, select.services-select');
            selects.forEach(function (select) {
                window.initPremiumSelect(select);
            });
        });

        // Global View Mode Toggle Handler
        window.initializeViewModeToggle = function(pageKey) {
            const wrapper = document.querySelector('.view-wrapper');
            if (!wrapper) return;
            
            // Get stored preference (default: table)
            const savedMode = localStorage.getItem(`poltree_view_mode_${pageKey}`) || 'table';
            
            // Apply saved mode
            wrapper.classList.remove('view-mode-table', 'view-mode-card');
            wrapper.classList.add(`view-mode-${savedMode}`);
            
            // Update active state of buttons
            document.querySelectorAll('.view-toggle-btn').forEach(btn => {
                const mode = btn.getAttribute('data-view-mode');
                if (mode === savedMode) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
                
                // Add click listener
                btn.addEventListener('click', function() {
                    const selectedMode = this.getAttribute('data-view-mode');
                    localStorage.setItem(`poltree_view_mode_${pageKey}`, selectedMode);
                    
                    wrapper.classList.remove('view-mode-table', 'view-mode-card');
                    wrapper.classList.add(`view-mode-${selectedMode}`);
                    
                    document.querySelectorAll('.view-toggle-btn').forEach(b => {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
        };
    </script>
    @stack('modals')
    @stack('scripts')
</body>

</html>
