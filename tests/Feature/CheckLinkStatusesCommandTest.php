<?php

use App\Models\Link;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

if (! in_array('sqlite', PDO::getAvailableDrivers(), true)) {
    test('sqlite driver is available for command integration tests', function () {
        $this->markTestSkipped('Driver sqlite tidak tersedia pada environment ini.');
    });

    return;
}

beforeEach(function () {
    Schema::dropIfExists('t_link');

    Schema::create('t_link', function (Blueprint $table) {
        $table->unsignedBigInteger('id_link')->primary();
        $table->string('nama_web')->nullable();
        $table->string('url')->nullable();
        $table->text('deskripsi')->nullable();
        $table->string('tag')->nullable();
        $table->string('status')->nullable();
        $table->string('status_link')->nullable();
        $table->unsignedBigInteger('hit_point')->default(0);
        $table->timestamp('status_checked_at')->nullable();
        $table->unsignedSmallInteger('status_http_code')->nullable();
        $table->unsignedInteger('status_response_time_ms')->nullable();
        $table->string('status_summary')->nullable();
        $table->unsignedBigInteger('id_kategori')->nullable();
    });
});

afterEach(function () {
    Schema::dropIfExists('t_link');
});

it('updates link status automatically from remote website responses', function () {
    Link::query()->create([
        'id_link' => 1,
        'nama_web' => 'Portal Aktif',
        'url' => 'online.example',
        'status' => 'aktif',
    ]);

    Link::query()->create([
        'id_link' => 2,
        'nama_web' => 'Portal Bermasalah',
        'url' => 'down.example',
        'status' => 'aktif',
    ]);

    Http::fake([
        'downtimecheck.vercel.app/api/check*' => function ($request) {
            $url = $request->offsetGet('url') ?? '';
            if (str_contains($url, 'online.example')) {
                return Http::response([
                    'success' => true,
                    'data' => [
                        'online' => true,
                        'statusCode' => 200,
                        'statusText' => 'OK',
                        'responseTimeMs' => 120,
                    ]
                ], 200);
            }
            if (str_contains($url, 'down.example')) {
                return Http::response([
                    'success' => true,
                    'data' => [
                        'online' => false,
                        'statusCode' => 503,
                        'statusText' => 'Service Unavailable',
                        'errorMessage' => 'Service Unavailable',
                    ]
                ], 200);
            }
            return Http::response(['success' => false], 404);
        }
    ]);

    Artisan::call('links:check-status');

    $onlineLink = Link::query()->find(1);
    $downLink = Link::query()->find(2);

    expect($onlineLink?->status_link)->toBe('aktif');
    expect($onlineLink?->status)->toBe('aktif'); // manual status unchanged
    expect($onlineLink?->status_http_code)->toBe(200);
    expect($onlineLink?->status_checked_at)->not->toBeNull();

    expect($downLink?->status_link)->toBe('bermasalah');
    expect($downLink?->status)->toBe('aktif'); // manual status unchanged
    expect($downLink?->status_http_code)->toBe(503);
    expect($downLink?->status_checked_at)->not->toBeNull();
});

it('falls back to get request when head is not supported', function () {
    Link::query()->create([
        'id_link' => 3,
        'nama_web' => 'Portal Head Fallback',
        'url' => 'head-only.example',
        'status' => 'aktif',
    ]);

    Http::fake([
        'downtimecheck.vercel.app/api/check*' => function ($request) {
            $url = $request->offsetGet('url') ?? '';
            if (str_contains($url, 'head-only.example')) {
                return Http::response([
                    'success' => true,
                    'data' => [
                        'online' => true,
                        'statusCode' => 200,
                        'statusText' => 'OK',
                        'responseTimeMs' => 150,
                    ]
                ], 200);
            }
            return Http::response(['success' => false], 404);
        }
    ]);

    Artisan::call('links:check-status', ['--id' => [3]]);

    $fallbackLink = Link::query()->find(3);

    expect($fallbackLink?->status_link)->toBe('aktif');
    expect($fallbackLink?->status)->toBe('aktif'); // manual status unchanged
    expect($fallbackLink?->status_http_code)->toBe(200);
});
