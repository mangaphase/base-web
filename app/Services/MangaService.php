<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MangaService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('MANGA_API_URL', 'http://localhost:3001');
    }

    public function getGenreAll()
    {
        return Http::get("{$this->baseUrl}/genre-all")->json();
    }

public function getGenreDetail($slug, $page = 1)
{
    return Http::get("{$this->baseUrl}/genre/{$slug}/{$page}")->json();
}
    
    public function getPopuler()
    {
        return Http::get("{$this->baseUrl}/komik-populer")->json();
    }
    
    public function getTerbaru()
    {
        return Http::get("{$this->baseUrl}/terbaru-2")->json();
    }
    
    public function getPustaka($page = 1)
    {
        return Http::get("{$this->baseUrl}/pustaka/{$page}")->json();
    }

    public function getDetailKomik($slug)
    {
        return Http::get("{$this->baseUrl}/detail-komik/{$slug}")->json();
    }

    public function getChapter($slug, $chapter)
    {
        return Http::get("{$this->baseUrl}/baca-chapter/{$slug}/{$chapter}")->json();
    }
    public function searchManga($query, $page = 1)
    {
        return Http::get("{$this->baseUrl}/search", [
            'q' => $query,
            'page' => $page
        ])->json();
    }
}
