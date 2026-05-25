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
    ]);

    Link::query()->create([
        'id_link' => 2,
        'nama_web' => 'Portal Bermasalah',
        'url' => 'down.example',
    ]);

    Http::fake(function ($request) {
        return match (true) {
            $request->url() === 'https://online.example' => Http::response('', 200),
            $request->url() === 'https://down.example' => Http::response('', 503),
            default => Http::response('', 404),
        };
    });

    Artisan::call('links:check-status');

    $onlineLink = Link::query()->find(1);
    $downLink = Link::query()->find(2);

    expect($onlineLink?->status)->toBe('aktif');
    expect($onlineLink?->status_http_code)->toBe(200);
    expect($onlineLink?->status_checked_at)->not->toBeNull();

    expect($downLink?->status)->toBe('bermasalah');
    expect($downLink?->status_http_code)->toBe(503);
    expect($downLink?->status_checked_at)->not->toBeNull();
});

it('falls back to get request when head is not supported', function () {
    Link::query()->create([
        'id_link' => 3,
        'nama_web' => 'Portal Head Fallback',
        'url' => 'head-only.example',
    ]);

    Http::fake(function ($request) {
        return match (true) {
            $request->url() === 'https://head-only.example' && $request->method() === 'HEAD' => Http::response('', 405),
            $request->url() === 'https://head-only.example' && $request->method() === 'GET' => Http::response('OK', 200),
            default => Http::response('', 404),
        };
    });

    Artisan::call('links:check-status', ['--id' => [3]]);

    $fallbackLink = Link::query()->find(3);

    expect($fallbackLink?->status)->toBe('aktif');
    expect($fallbackLink?->status_http_code)->toBe(200);
});
