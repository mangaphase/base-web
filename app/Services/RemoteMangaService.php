<?php

namespace App\Services;

use App\Services\Contracts\RemoteMangaServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RemoteMangaService implements RemoteMangaServiceInterface
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.remote_manga.base_url') ?? env('REMOTE_API_BASE', 'https://example.com');
    }

    public function list(int $page = 1): array
    {
        $cacheKey = "remote_manga_list_{$page}";

        return Cache::remember($cacheKey, now()->addSeconds(60), function () use ($page) {
            try {
                $response = Http::retry(2, 100)
                    ->timeout(10)
                    ->acceptJson()
                    ->get("{$this->baseUrl}/api/v1/manga", ['page' => $page]);

                $response->throw();
                $json = $response->json();
                return $json['data'] ?? $json ?? [];
            } catch (\Throwable $e) {
                Log::error('RemoteMangaService::list failed', ['exception' => $e]);
                return [];
            }
        });
    }

    public function get(string $mangaId): array
    {
        $cacheKey = "remote_manga_item_{$mangaId}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($mangaId) {
            try {
                $response = Http::retry(2, 100)
                    ->timeout(10)
                    ->acceptJson()
                    ->get("{$this->baseUrl}/api/v1/manga/{$mangaId}");

                $response->throw();
                $json = $response->json();
                return $json['data'] ?? $json ?? [];
            } catch (\Throwable $e) {
                Log::error('RemoteMangaService::get failed', ['mangaId' => $mangaId, 'exception' => $e]);
                return [];
            }
        });
    }

    public function getChapter(string $chapterId): array
    {
        $cacheKey = "remote_manga_chapter_{$chapterId}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($chapterId) {
            try {
                $response = Http::retry(2, 100)
                    ->timeout(10)
                    ->acceptJson()
                    ->get("{$this->baseUrl}/api/v1/manga/chapter/{$chapterId}");

                $response->throw();
                $json = $response->json();
                return $json['data'] ?? $json ?? [];
            } catch (\Throwable $e) {
                Log::error('RemoteMangaService::getChapter failed', ['chapterId' => $chapterId, 'exception' => $e]);
                return [];
            }
        });
    }
}