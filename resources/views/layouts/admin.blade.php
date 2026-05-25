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
                <div class="profile-menu-wrap">
                    <button type="button" class="admin-profile-button" aria-label="Profil admin" data-profile-toggle>
                        @if ($profileIconAsset)
                            <img src="{{ $profileIconAsset }}" alt="" aria-hidden="true">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                {!! $iconPaths['profile'] !!}
                            </svg>
                        @endif
                    </button>

                    {{-- Panel profil dropdown --}}
                    <div class="profile-panel" data-profile-panel hidden>
                        <div class="profile-panel-actions">
                            <button type="button" class="profile-panel-btn" onclick="alert('Fitur profil belum tersedia')">
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
            </header>

            {{-- ═══════════════════════════════════════════════
                 MODAL: Ubah Kata Sandi Admin
                 ═══════════════════════════════════════════════ --}}
            <div id="passwordModal" class="password-modal hidden">
                <div class="password-modal-content">
                    <h2 class="m-0 mb-5 text-xl font-bold text-[#080d5f] text-center">Ubah Kata Sandi</h2>
                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Lama</label>
                            <input type="password" name="old_password" required class="form-input" placeholder="Masukkan kata sandi lama">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Baru</label>
                            <input type="password" name="password" required class="form-input" placeholder="Masukkan kata sandi baru">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" required class="form-input" placeholder="Ulangi kata sandi baru">
                        </div>
                        <div class="flex gap-2.5 mt-8">
                            <button type="button" onclick="closePasswordModal()" class="flex-1 h-12 rounded-xl border border-gray-200 bg-white cursor-pointer font-semibold">Batal</button>
                            <button type="submit" class="flex-1 h-12 rounded-xl border-0 bg-[#080d5f] text-white cursor-pointer font-semibold">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Konten halaman admin --}}
            <main class="admin-content">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 animate-fade-in">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl animate-fade-in">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm font-bold">Terjadi kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
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

        function openPasswordModal() {
            const m = document.getElementById('passwordModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closePasswordModal() {
            const m = document.getElementById('passwordModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
    </script>
    @stack('scripts')
</body>

</html>
