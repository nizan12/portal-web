<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class LinkStatusChecker
{
    public function check(Link $link): array
    {
        return $this->checkUrl($link->normalized_url);
    }

    public function checkUrl(?string $url): array
    {
        $checkedAt = now();

        if (! is_string($url) || trim($url) === '') {
            return $this->problemResult(
                checkedAt: $checkedAt,
                summary: 'URL tidak valid atau belum diisi.',
            );
        }

        $startedAt = microtime(true);

        try {
            $response = $this->sendRequest('HEAD', $url);

            if ($response->status() >= 400) {
                $response = $this->sendRequest('GET', $url);
            }

            return $this->resultFromResponse(
                response: $response,
                checkedAt: $checkedAt,
                elapsedMilliseconds: $this->elapsedMilliseconds($startedAt),
            );
        } catch (ConnectionException $exception) {
            return $this->problemResult(
                checkedAt: $checkedAt,
                summary: 'Gagal terhubung ke website: '.$exception->getMessage(),
                elapsedMilliseconds: $this->elapsedMilliseconds($startedAt),
            );
        } catch (Throwable $exception) {
            return $this->problemResult(
                checkedAt: $checkedAt,
                summary: 'Pemeriksaan status gagal: '.$exception->getMessage(),
                elapsedMilliseconds: $this->elapsedMilliseconds($startedAt),
            );
        }
    }

    protected function sendRequest(string $method, string $url): Response
    {
        return $this->request()
            ->send($method, $url);
    }

    protected function request(): PendingRequest
    {
        // Use a more common User-Agent to avoid being blocked by security filters
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36';

        return Http::accept('*/*')
            ->withUserAgent((string) config('services.link_status_monitor.user_agent', $userAgent))
            ->connectTimeout((int) config('services.link_status_monitor.connect_timeout', 10))
            ->timeout((int) config('services.link_status_monitor.timeout', 20))
            ->retry((int) config('services.link_status_monitor.retries', 2), 500)
            ->withOptions([
                'allow_redirects' => [
                    'max' => 5,
                    'strict' => true,
                    'referer' => true,
                ],
                'verify' => (bool) config('services.link_status_monitor.verify_ssl', true),
            ]);
    }

    protected function resultFromResponse(Response $response, $checkedAt, int $elapsedMilliseconds): array
    {
        $statusCode = $response->status();
        $isReachable = $this->isReachableStatus($statusCode);

        return [
            'status' => $isReachable ? 'aktif' : 'bermasalah',
            'status_checked_at' => $checkedAt,
            'status_http_code' => $statusCode,
            'status_response_time_ms' => $elapsedMilliseconds,
            'status_summary' => $isReachable
                ? sprintf('Website merespons normal (HTTP %d).', $statusCode)
                : sprintf('Website merespons dengan status HTTP %d.', $statusCode),
        ];
    }

    protected function problemResult($checkedAt, string $summary, ?int $elapsedMilliseconds = null): array
    {
        return [
            'status' => 'bermasalah',
            'status_checked_at' => $checkedAt,
            'status_http_code' => null,
            'status_response_time_ms' => $elapsedMilliseconds,
            'status_summary' => $summary,
        ];
    }

    protected function isReachableStatus(int $statusCode): bool
    {
        // Only 2xx and 3xx are considered truly "active" for a typical dashboard.
        // 4xx (Client Error) and 5xx (Server Error) are problematic.
        return $statusCode >= 200 && $statusCode < 400;
    }

    protected function elapsedMilliseconds(float $startedAt): int
    {
        return max(1, (int) round((microtime(true) - $startedAt) * 1000));
    }
}
