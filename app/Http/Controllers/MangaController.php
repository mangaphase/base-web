<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Services\Contracts\RemoteMangaServiceInterface;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    protected RemoteMangaServiceInterface $mangaService;

    public function __construct(RemoteMangaServiceInterface $mangaService)
    {
        $this->mangaService = $mangaService;
    }

    public function index(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $mangas = $this->mangaService->list($page);

        return Inertia::render('Manga/Index', [
            'mangas' => $mangas,
            'filters' => ['page' => $page],
        ]);
    }

    public function show(string $id)
    {
        $manga = $this->mangaService->get($id);
        return Inertia::render('Manga/Show', ['manga' => $manga]);
    }

    public function chapter(string $chapterId)
    {
        $chapter = $this->mangaService->getChapter($chapterId);
        return Inertia::render('Manga/Chapter', ['chapter' => $chapter]);
    }
}