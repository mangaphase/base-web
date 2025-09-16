<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;

class ReadingHistoryController extends Controller
{
    public function index()
    {
        $histories = ReadingHistory::where('user_id', Auth::id())
            ->orderBy('last_read_at', 'desc')
            ->get();

        
        return Inertia::render('Manga/ReadingHistory', [
            'histories' => $histories
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'comic_api_title' => 'required|string',
            'comic_api_slug' => 'required|string',
            'chapter_api_slug' => 'required|string',
            'last_page_number' => 'required|integer',
        ]);

        ReadingHistory::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'comic_api_slug' => $request->comic_api_slug,
            ],
            [
                'comic_api_title' => $request->comic_api_title,
                'chapter_api_slug' => $request->chapter_api_slug,
                'last_page_number' => $request->last_page_number,
                'last_read_at' => now(),
            ]
        );

        return response()->json(['message' => 'History saved']);
    }
}
