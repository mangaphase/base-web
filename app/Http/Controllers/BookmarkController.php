<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())->get();
        return Inertia::render('Manga/Bookmark', [
            'bookmarks' => $bookmarks
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'comic_api_slug' => 'required',
            'comic_api_title' => 'required',
            'comic_api_thumbnail' => 'required',
        ]);

        Bookmark::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'comic_api_slug' => $request->comic_api_slug,
            ],
            [
                'comic_api_title' => $request->comic_api_title,
                'comic_api_thumbnail' => $request->comic_api_thumbnail,
            ]
        );

        return back();
    }

    public function destroy($comic_api_slug)
    {
        $bookmark = Bookmark::where('comic_api_slug', $comic_api_slug)
                        ->where('user_id', auth()->id())
                        ->first();

        if ($bookmark) {
            $bookmark->delete();
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Bookmark removed successfully']);
            }
            return redirect()->back()->with('message', 'Bookmark removed successfully');
        }

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Bookmark not found'], 404);
        }
        return redirect()->back()->with('error', 'Bookmark not found');
    }
}
