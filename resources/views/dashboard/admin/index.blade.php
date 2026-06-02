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
    <h1 class="admin-heading" style="color: #080d5f; font-weight: 800; font-size: 26px; margin-bottom: 6px;">Selamat Datang, Admin!</h1>
    <p class="admin-subheading" style="color: #8a8fa5; font-size: 14px; margin-bottom: 28px; font-weight: 500;">Berikut adalah ringkasan data dan analisis sistem terintegrasi Anda.</p>

    {{-- Grid kartu statistik utama --}}
    <section class="admin-stats-row" aria-label="Ringkasan data admin" style="margin-bottom: 28px;">
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
                    @else
                        {{-- Fallback SVG if icons are missing --}}
                        @if($stat['icon'] === 'users')
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#080d5f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.85;">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        @elseif($stat['icon'] === 'link')
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#080d5f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.85;">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                        @else
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#080d5f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.85;">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                            </svg>
                        @endif
                    @endif
                    <div class="admin-stat-value">{{ $stat['value'] }}</div>
                </div>
            </article>
        @endforeach
    </section>

    {{-- Grid Analisis Detail --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 24px; margin-bottom: 28px;">
        
        {{-- CARD 1: Health Monitor --}}
        <div style="background: white; border-radius: 20px; border: 1px solid rgba(8, 13, 95, 0.06); padding: 24px; box-shadow: 0 10px 30px rgba(8, 13, 95, 0.02); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e2243; margin: 0 0 6px; display: flex; align-items: center; gap: 8px;">
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; animation: pulse 2s infinite;"></span>
                    Monitor Kesehatan Layanan (Health Check)
                </h3>
                <p style="font-size: 12.5px; color: #8a8fa5; margin: 0 0 20px;">Memantau ketersediaan sistem dan respon HTTP secara real-time.</p>

                @php
                    $totalChecked = $statsDetail['layananAman'] + $statsDetail['layananDowntime'] + $statsDetail['layananBelumDicek'];
                    $pctAman = $totalChecked > 0 ? round(($statsDetail['layananAman'] / $totalChecked) * 100, 1) : 0;
                    $pctDowntime = $totalChecked > 0 ? round(($statsDetail['layananDowntime'] / $totalChecked) * 100, 1) : 0;
                    $pctBelum = $totalChecked > 0 ? round(($statsDetail['layananBelumDicek'] / $totalChecked) * 100, 1) : 0;
                @endphp

                {{-- Stacked Progress Bar --}}
                <div style="height: 12px; display: flex; border-radius: 30px; overflow: hidden; background: #f1f5f9; margin-bottom: 24px;">
                    <div style="width: {{ $pctAman }}%; background: #10b981;" title="Aman (200 OK): {{ $pctAman }}%"></div>
                    <div style="width: {{ $pctDowntime }}%; background: #ff3f0a;" title="Bermasalah/Downtime: {{ $pctDowntime }}%"></div>
                    <div style="width: {{ $pctBelum }}%; background: #cbd5e1;" title="Belum Dicek: {{ $pctBelum }}%"></div>
                </div>

                {{-- Legend & Detailed Info --}}
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px;">
                    <div style="background: #f8fafc; border-radius: 12px; padding: 12px; text-align: center; border: 1px solid rgba(16, 185, 129, 0.08);">
                        <span style="font-size: 11px; font-weight: 600; color: #10b981; display: block; margin-bottom: 4px;">Aman (200 OK)</span>
                        <strong style="font-size: 18px; color: #1e2243;">{{ $statsDetail['layananAman'] }}</strong>
                        <span style="font-size: 11px; color: #8a8fa5; display: block; margin-top: 2px;">{{ $pctAman }}%</span>
                    </div>
                    <div style="background: #f8fafc; border-radius: 12px; padding: 12px; text-align: center; border: 1px solid rgba(255, 63, 10, 0.08);">
                        <span style="font-size: 11px; font-weight: 600; color: #ff3f0a; display: block; margin-bottom: 4px;">Downtime / Error</span>
                        <strong style="font-size: 18px; color: #1e2243;">{{ $statsDetail['layananDowntime'] }}</strong>
                        <span style="font-size: 11px; color: #8a8fa5; display: block; margin-top: 2px;">{{ $pctDowntime }}%</span>
                    </div>
                    <div style="background: #f8fafc; border-radius: 12px; padding: 12px; text-align: center; border: 1px solid rgba(203, 213, 225, 0.5);">
                        <span style="font-size: 11px; font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">Belum Dicek</span>
                        <strong style="font-size: 18px; color: #1e2243;">{{ $statsDetail['layananBelumDicek'] }}</strong>
                        <span style="font-size: 11px; color: #8a8fa5; display: block; margin-top: 2px;">{{ $pctBelum }}%</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 16px; margin-top: 12px;">
                <span style="font-size: 12.5px; font-weight: 600; color: #8a8fa5;">Rata-rata Waktu Respon</span>
                <span style="font-size: 14px; font-weight: 700; color: #080d5f;">
                    {{ $statsDetail['avgResponseTime'] > 0 ? $statsDetail['avgResponseTime'] . ' ms' : 'Belum tersedia' }}
                </span>
            </div>
        </div>

        {{-- CARD 2: Distribusi Layanan (Publik vs Pribadi) --}}
        <div style="background: white; border-radius: 20px; border: 1px solid rgba(8, 13, 95, 0.06); padding: 24px; box-shadow: 0 10px 30px rgba(8, 13, 95, 0.02); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e2243; margin: 0 0 6px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#080d5f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    Distribusi Layanan Sistem
                </h3>
                <p style="font-size: 12.5px; color: #8a8fa5; margin: 0 0 20px;">Perbandingan layanan umum (Disediakan Admin) vs layanan kustom (Koleksi Mandiri Pengguna).</p>

                @php
                    $totalLayananDist = $statsDetail['layananPublik'] + $statsDetail['layananPribadi'];
                    $pctPublik = $totalLayananDist > 0 ? round(($statsDetail['layananPublik'] / $totalLayananDist) * 100) : 0;
                    $pctPribadi = $totalLayananDist > 0 ? round(($statsDetail['layananPribadi'] / $totalLayananDist) * 100) : 0;
                @endphp

                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
                    {{-- Progress 1: Publik --}}
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 12.5px;">
                            <span style="font-weight: 600; color: #475569;">Layanan Publik (Umum)</span>
                            <span style="font-weight: 700; color: #080d5f;">{{ $statsDetail['layananPublik'] }} Tautan ({{ $pctPublik }}%)</span>
                        </div>
                        <div style="height: 8px; border-radius: 30px; background: #e2e8f0; overflow: hidden;">
                            <div style="width: {{ $pctPublik }}%; height: 100%; background: linear-gradient(90deg, #080d5f, #0f179e); border-radius: 30px;"></div>
                        </div>
                    </div>

                    {{-- Progress 2: Pribadi --}}
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 12.5px;">
                            <span style="font-weight: 600; color: #475569;">Layanan Kustom (Pribadi)</span>
                            <span style="font-weight: 700; color: #f97316;">{{ $statsDetail['layananPribadi'] }} Tautan ({{ $pctPribadi }}%)</span>
                        </div>
                        <div style="height: 8px; border-radius: 30px; background: #e2e8f0; overflow: hidden;">
                            <div style="width: {{ $pctPribadi }}%; height: 100%; background: linear-gradient(90deg, #f97316, #fb923c); border-radius: 30px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: #eef1f8; border-radius: 12px; padding: 12px; display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#080d5f" stroke-width="2.5" style="flex-shrink: 0;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span style="font-size: 12px; font-weight: 600; color: #080d5f; line-height: 1.4;">
                    Rasio ketersediaan layanan publik mendominasi {{ $pctPublik }}% dari total repositori tautan POLTREE.
                </span>
            </div>
        </div>


    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 24px; margin-top: 24px;">

        {{-- CARD 3: Layanan Populer (Berdasarkan Clicks / Hits) --}}
        <div style="background: white; border-radius: 20px; border: 1px solid rgba(8, 13, 95, 0.06); padding: 24px; box-shadow: 0 10px 30px rgba(8, 13, 95, 0.02);">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e2243; margin: 0 0 6px; display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                5 Layanan Terpopuler (Top Clicked)
            </h3>
            <p style="font-size: 12.5px; color: #8a8fa5; margin: 0 0 20px;">Layanan dengan frekuensi klik/kunjungan tertinggi oleh pengguna.</p>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                @forelse ($statsDetail['topLinks'] as $index => $link)
                    @php
                        $maxHits = $statsDetail['topLinks']->first()->hit_point ?? 1;
                        $pctHits = $maxHits > 0 ? round(($link->hit_point / $maxHits) * 100) : 0;
                    @endphp
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; position: relative; padding-bottom: 8px; border-bottom: 1px solid #f8fafc;">
                        <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: #eef1f8; color: #080d5f; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0;">
                                {{ $index + 1 }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <strong style="display: block; font-size: 13.5px; color: #1e2243; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $link->nama_web }}</strong>
                                <span style="font-size: 11px; color: #8a8fa5; word-break: break-all;">{{ $link->url }}</span>
                            </div>
                        </div>
                        <div style="text-align: right; flex-shrink: 0;">
                            <span style="display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 20px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                                {{ $link->hit_point }} Klik
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #8a8fa5; font-size: 13px; padding: 20px 0;">Belum ada riwayat hit/klik layanan kustom.</div>
                @endforelse
            </div>
        </div>

        {{-- CARD 4: Kategori Teraktif --}}
        <div style="background: white; border-radius: 20px; border: 1px solid rgba(8, 13, 95, 0.06); padding: 24px; box-shadow: 0 10px 30px rgba(8, 13, 95, 0.02);">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e2243; margin: 0 0 6px; display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#080d5f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
                5 Kategori Teraktif
            </h3>
            <p style="font-size: 12.5px; color: #8a8fa5; margin: 0 0 20px;">Kategori dengan jumlah tautan layanan terintegrasi terbanyak.</p>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                @forelse ($statsDetail['topCategories'] as $index => $cat)
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding-bottom: 8px; border-bottom: 1px solid #f8fafc;">
                        <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <strong style="display: block; font-size: 13.5px; color: #1e2243;">{{ $cat->nama_kategori }}</strong>
                                <span style="font-size: 11px; color: #8a8fa5;">Dibuat: {{ $cat->created_at ?? 'Sistem Utama' }}</span>
                            </div>
                        </div>
                        <div style="text-align: right; flex-shrink: 0;">
                            <span style="display: inline-block; font-size: 12px; font-weight: 700; color: #1e2243;">
                                {{ $cat->links_count }} Layanan
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #8a8fa5; font-size: 13px; padding: 20px 0;">Kategori belum terdefinisi.</div>
                @endforelse
            </div>

            <div style="background: #fff8e6; border-radius: 12px; padding: 12px; display: flex; align-items: center; gap: 10px; margin-top: 16px; border: 1px solid #ffeeba;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2.5" style="flex-shrink: 0;">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                <span style="font-size: 12px; font-weight: 600; color: #b45309; line-height: 1.4;">
                    Kategori Teraktif Utama : <strong>{{ $statsDetail['mostActiveCategoryFromProc'] }}</strong>
                </span>
            </div>
        </div>

    </div>

    {{-- Pulse Keyframe Animation --}}
    <style>
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
@endsection
