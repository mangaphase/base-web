<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MangaController;

Route::get('/manga', [MangaController::class, 'index'])->name('manga.index');
Route::get('/manga/{slug}', [MangaController::class, 'detail'])->name('manga.detail');
Route::get('/manga/{slug}/{chapter}', [MangaController::class, 'chapter'])->name('manga.chapter');
Route::get('/genre/{slug}', [MangaController::class, 'genre'])->name('manga.genre');
//pagination genre
Route::get('/genre/{slug}/page/{page}', [MangaController::class, 'genre'])->name('genre.page');
Route::get('/pustaka/{page?}', [MangaController::class, 'pustaka'])->name('manga.pustaka');

// Route::get('/', [MangaController::class, 'index'])->name('manga.index');


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
