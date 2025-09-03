<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MangaController;

Route::get('/manga', [MangaController::class, 'index'])->name('manga.index');
Route::get('/manga/{id}', [MangaController::class, 'show'])->name('manga.show');
Route::get('/manga/chapter/{chapterId}', [MangaController::class, 'chapter'])->name('manga.chapter');

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
