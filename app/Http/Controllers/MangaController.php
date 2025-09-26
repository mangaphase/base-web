<?php

namespace App\Http\Controllers;

use App\Services\MangaService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    protected $manga;

    public function __construct(MangaService $manga)
    {
        $this->manga = $manga;
    }

 public function index()
{
    $populerRaw = $this->manga->getPopuler();
    // dd($populerRaw);
    $mangaPopuler = $populerRaw['manga']['items'] ?? [];
    $manhwaPopuler = $populerRaw['manhwa']['items'] ?? [];
    $manhuaPopuler = $populerRaw['manhua']['items'] ?? [];
    $genres = $this->manga->getGenreAll();
    $terbaru = $this->manga->getTerbaru();

    return Inertia::render('Home', [
        'mangaPopuler' => $mangaPopuler,
        'manhwaPopuler' => $manhwaPopuler,
        'manhuaPopuler' => $manhuaPopuler,
        'genres' => $genres,
        'terbaru' => $terbaru,
    ]);
}

public function pustaka($page = 1)
{
    $pustaka = $this->manga->getPustaka($page);
    // dd($pustaka);
    return Inertia::render('Manga/Pustaka', [
        'pustaka' => $pustaka
    ]);
}

public function genre($slug, $page = 1)
{
    $genreRaw = $this->manga->getGenreDetail($slug, $page);

    return Inertia::render('Manga/Genre', [
        'title' => ucfirst($genreRaw['genre'] ?? $slug),
        'slug' => $slug,
        'items' => $genreRaw['data'] ?? [],
        'currentPage' => $genreRaw['currentPage'] ?? 1,
        'totalManga' => $genreRaw['totalManga'] ?? 0,
        'hasNextPage' => $genreRaw['hasNextPage'] ?? false,
        'nextPageUrl' => $genreRaw['nextPageUrl'] ?? null,
    ]);
}
    public function detail($slug)
    {
        $komik = $this->manga->getDetailKomik($slug);
        $isBookmarked = \App\Models\Bookmark::where('user_id', auth()->id())
            ->where('comic_api_slug', $slug)
            ->exists();
        return Inertia::render('Manga/Detail', [
            'komik' => $komik
            , 'isBookmarked' => $isBookmarked
        ]);
    }

    public function chapter($slug, $chapter)
    {
        $chapterData = $this->manga->getChapter($slug, $chapter);
        return Inertia::render('Manga/Chapter', [
            'chapter' => $chapterData
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $page = $request->input('page', 1);

        $result = $this->manga->searchManga($query, $page);

        return Inertia::render('Manga/Search', [
            'query' => $query,
            'result' => $result,
        ]);
    }
}
