<?php

namespace App\Services\Contracts;

interface RemoteMangaServiceInterface
{
    public function list(int $page = 1): array;
    public function get(string $mangaId): array;
    public function getChapter(string $chapterId): array;
}