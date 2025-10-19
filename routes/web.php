<?php

use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ReadingHistoryController;

// Route::get('/manga', [MangaController::class, 'index'])->name('manga.index');
Route::get('/manga/{slug}', [MangaController::class, 'detail'])->name('manga.detail');
Route::get('/manga/{slug}/{chapter}', [MangaController::class, 'chapter'])->name('manga.chapter');
Route::get('/genre/{slug}', [MangaController::class, 'genre'])->name('manga.genre');
//pagination genre
Route::get('/genre/{slug}/page/{page}', [MangaController::class, 'genre'])->name('genre.page');
Route::get('/pustaka/{page?}', [MangaController::class, 'pustaka'])->name('manga.pustaka');
Route::get('/search', [MangaController::class, 'search'])->name('manga.search');

Route::get('/bookmark', [BookmarkController::class, 'index'])->middleware(['auth', 'verified'])->name('bookmark.index');
Route::post('/bookmark', [BookmarkController::class, 'store'])->name('bookmark.store');
Route::delete('/bookmark/{comic_api_slug}', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');
Route::get('/', [MangaController::class, 'index'])->name('home');
Route::get('/reading-history', [ReadingHistoryController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('reading-history.index');
Route::post('/reading-history', [ReadingHistoryController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('reading-history.store');
// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
