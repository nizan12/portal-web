{{--
|--------------------------------------------------------------------------
| Dashboard Admin – Halaman Utama
|--------------------------------------------------------------------------
| Menampilkan ringkasan statistik: jumlah pengguna, layanan, kategori.
| Data berasal dari $stats (array) yang dikirim controller.
|--------------------------------------------------------------------------
--}}
@extends('layouts.admin')

@section('content')
    <h1 class="admin-heading">Selamat Datang, Admin!</h1>
    <p class="admin-subheading">Berikut adalah ringkasan data:</p>

    {{-- Grid kartu statistik --}}
    <section class="admin-stats-row" aria-label="Ringkasan data admin">
        @foreach ($stats as $stat)
            @php
                $statIconAsset = match ($stat['icon']) {
                    'users' => file_exists(public_path('icons/admin/stat-users.svg')) ? asset('icons/admin/stat-users.svg') : null,
                    'link' => file_exists(public_path('icons/admin/stat-links.svg')) ? asset('icons/admin/stat-links.svg') : null,
                    'folder-user' => file_exists(public_path('icons/admin/stat-categories.svg')) ? asset('icons/admin/stat-categories.svg') : null,
                    default => null,
                };
            @endphp
            <article class="admin-stat-card">
                <div class="admin-stat-label">{{ $stat['label'] }}</div>
                <div class="admin-stat-body">
                    @if ($statIconAsset)
                        <img src="{{ $statIconAsset }}" alt="" aria-hidden="true">
                    @endif
                    <div class="admin-stat-value">{{ $stat['value'] }}</div>
                </div>
            </article>
        @endforeach
    </section>
@endsection
