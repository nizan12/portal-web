<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Facades\Http;
use Throwable;

class LinkStatusChecker
{
    /**
     * Base URL of the downtime-check API.
     */
    protected string $apiBaseUrl = 'https://downtimecheck.vercel.app/api/check';

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

        try {
            $response = Http::timeout(30)
                ->connectTimeout(15)
                ->get($this->apiBaseUrl, [
                    'url' => $url,
                ]);

            if (! $response->successful()) {
                return $this->problemResult(
                    checkedAt: $checkedAt,
                    summary: sprintf(
                        'API downtime-check merespons dengan HTTP %d.',
                        $response->status()
                    ),
                );
            }

            $body = $response->json();

            if (empty($body['success']) || empty($body['data'])) {
                return $this->problemResult(
                    checkedAt: $checkedAt,
                    summary: 'Respons API tidak valid atau gagal.',
                );
            }

            return $this->resultFromApiData($body['data'], $checkedAt);
        } catch (Throwable $exception) {
            return $this->problemResult(
                checkedAt: $checkedAt,
                summary: 'Pemeriksaan status gagal: ' . $exception->getMessage(),
            );
        }
    }

    /**
     * Build result array from the downtimecheck API response data.
     */
    protected function resultFromApiData(array $data, $checkedAt): array
    {
        $isOnline = !empty($data['online']);
        $statusCode = $data['statusCode'] ?? null;
        $responseTimeMs = $data['responseTimeMs'] ?? null;
        $statusText = $data['statusText'] ?? '';
        $errorMessage = $data['errorMessage'] ?? null;

        if ($isOnline) {
            $summary = sprintf(
                'Website merespons normal (HTTP %d %s).',
                $statusCode ?? 0,
                $statusText
            );
        } else {
            $errorCategory = $data['errorCategory'] ?? null;
            $summary = $errorMessage
                ? sprintf('Website tidak dapat diakses: %s', $errorMessage)
                : sprintf(
                    'Website merespons dengan status HTTP %d %s.',
                    $statusCode ?? 0,
                    $statusText
                );

            if ($errorCategory) {
                $summary .= sprintf(' (%s)', $errorCategory);
            }
        }

        return [
            'status' => $isOnline ? 'aktif' : 'bermasalah',
            'status_checked_at' => $checkedAt,
            'status_http_code' => $statusCode,
            'status_response_time_ms' => $responseTimeMs ? (int) $responseTimeMs : null,
            'status_summary' => trim($summary),
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
}
