@extends('layouts.admin')

@section('content')
    <div class="api-checker-header" style="margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 800; color: #080d5f; font-family: 'Poppins', sans-serif; margin: 0 0 4px;">Uji Test API</h1>
        <p style="font-size: 13.5px; color: #8a8fa5; margin: 0;">Lakukan uji konektivitas dan kesehatan API/tautan secara real-time.</p>
    </div>

    {{-- Main Container --}}
    <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">

        <div style="background: white; border-radius: 20px; border: 1px solid rgba(8, 13, 95, 0.06); padding: 28px; box-shadow: 0 10px 30px rgba(8, 13, 95, 0.02);">
            <h3 style="font-size: 17px; font-weight: 700; color: #1e2243; margin: 0 0 8px; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#080d5f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                Alat Diagnostik Tautan / API
            </h3>
            <p style="font-size: 13px; color: #8a8fa5; margin: 0 0 20px; line-height: 1.5;">Masukkan URL website atau API endpoint di bawah ini untuk memeriksa status HTTP, waktu respon (latency), dan ketersediaan layanan publik.</p>

            <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 280px;">
                    <input type="url" id="testApiUrl" placeholder="https://example.com" style="width: 100%; padding: 12px 16px; border: 1px solid rgba(8, 13, 95, 0.12); border-radius: 12px; font-size: 14px; outline: none; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#080d5f'; this.style.boxShadow='0 0 0 3px rgba(8, 13, 95, 0.05)';" onblur="this.style.borderColor='rgba(8, 13, 95, 0.12)'; this.style.boxShadow='none';">
                </div>
                <button type="button" id="btnTestApi" style="background: #080d5f; color: white; border: none; padding: 12px 28px; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; min-width: 180px;" onmouseover="this.style.background='#0f179e'" onmouseout="this.style.background='#080d5f'">
                    <span id="btnTestText">Jalankan Uji Koneksi</span>
                    <svg id="loadingSpinner" style="display: none; animation: spin 1s linear infinite;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.2)"></circle>
                        <path d="M12 2a10 10 0 0 1 10 10" stroke="#ffffff"></path>
                    </svg>
                </button>
            </div>

            {{-- Result Area --}}
            <div id="apiTestResult" style="display: none; padding: 24px; border-radius: 16px; border: 1px solid rgba(8, 13, 95, 0.08); background: #f8fafc; transition: all 0.3s ease;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px;">Hasil Pemeriksaan</span>
                    <span id="resultBadge" style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700;"></span>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; border-bottom: 1px solid #eef1f6; padding-bottom: 16px; margin-bottom: 16px;">
                    <div>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Status HTTP / Koneksi:</div>
                        <div style="font-size: 18px; font-weight: 700;" id="resultStatusText">-</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Waktu Respon (Latency):</div>
                        <div style="font-size: 18px; font-weight: 700; color: #080d5f;" id="resultLatency">-</div>
                    </div>
                </div>

                <div>
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 6px;">Ringkasan Diagnostik:</div>
                    <div style="font-size: 13.5px; color: #1e2243; line-height: 1.5;" id="resultSummary">-</div>
                </div>
            </div>

            <div style="background: #f8fafc; border-radius: 12px; padding: 14px 16px; display: flex; align-items: center; gap: 10px; margin-top: 24px; border: 1px solid rgba(8, 13, 95, 0.04);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8a8fa5" stroke-width="2.5" style="flex-shrink: 0;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span style="font-size: 12px; color: #8a8fa5; line-height: 1.5;">
                    Pemeriksaan status ini menggunakan serverless network API untuk meminimalkan bias koneksi lokal (ISP Anda) dan mensimulasikan akses dari internet publik.
                </span>
            </div>
        </div>

    </div>

    {{-- Spin Keyframe Animation --}}
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnTestApi = document.getElementById('btnTestApi');
            const btnTestText = document.getElementById('btnTestText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const testApiUrl = document.getElementById('testApiUrl');
            const apiTestResult = document.getElementById('apiTestResult');
            const resultBadge = document.getElementById('resultBadge');
            const resultStatusText = document.getElementById('resultStatusText');
            const resultSummary = document.getElementById('resultSummary');
            const resultLatency = document.getElementById('resultLatency');

            btnTestApi.addEventListener('click', function () {
                const url = testApiUrl.value.trim();
                if (!url) {
                    alert('Silakan masukkan URL website terlebih dahulu.');
                    return;
                }

                // Show loading state
                btnTestApi.disabled = true;
                btnTestApi.style.opacity = '0.8';
                btnTestText.textContent = 'Memeriksa...';
                loadingSpinner.style.display = 'inline-block';
                apiTestResult.style.display = 'none';

                fetch('{{ route("admin.api-checker.run") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url: url })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Terjadi kesalahan pada server.');
                    }
                    return response.json();
                })
                .then(res => {
                    if (res.success && res.data) {
                        const data = res.data;
                        const isOnline = data.status_link === 'aktif';

                        // Display result card
                        apiTestResult.style.display = 'block';

                        // Update status badge
                        if (isOnline) {
                            resultBadge.textContent = 'ONLINE';
                            resultBadge.style.background = '#e6f4ea';
                            resultBadge.style.color = '#137333';
                            resultStatusText.textContent = 'Aman (HTTP ' + (data.status_http_code || 200) + ')';
                            resultStatusText.style.color = '#137333';
                        } else {
                            resultBadge.textContent = 'OFFLINE';
                            resultBadge.style.background = '#fce8e6';
                            resultBadge.style.color = '#c5221f';
                            resultStatusText.textContent = 'Bermasalah';
                            resultStatusText.style.color = '#c5221f';
                        }

                        // Summary
                        resultSummary.textContent = data.status_summary || 'Tidak ada ringkasan status.';

                        // Latency
                        resultLatency.textContent = data.status_response_time_ms ? data.status_response_time_ms + ' ms' : 'N/A';
                    } else {
                        alert(res.message || 'Gagal memeriksa URL.');
                    }
                })
                .catch(err => {
                    alert(err.message || 'Koneksi gagal atau terputus.');
                })
                .finally(() => {
                    // Reset loading state
                    btnTestApi.disabled = false;
                    btnTestApi.style.opacity = '1';
                    btnTestText.textContent = 'Jalankan Uji Koneksi';
                    loadingSpinner.style.display = 'none';
                });
            });
        });
    </script>
@endsection
