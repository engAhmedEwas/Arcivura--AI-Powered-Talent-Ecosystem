<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index()
    {
        $blacklists = Blacklist::latest()->paginate(10);
        return view('admin.blacklists.index', compact('blacklists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'word' => 'required|string|max:255|unique:blacklists,word',
            'reason' => 'nullable|string|max:500',
        ]);

        $word = strtolower(trim($validated['word']));

        Blacklist::create($validated);
        \App\Models\Keyword::where('name', $word)->delete();
        return back()->with('success', 'Word added to Blacklist successfully!');
    }

    public function destroy(Blacklist $blacklist)
    {
        $blacklist->delete();
        return back()->with('success', 'Word removed from Blacklist.');
    }

    public function bulkCleanup()
    {
        $blockedWords = Blacklist::pluck('word')->toArray();

        $deletedCount = \App\Models\Keyword::whereIn('name', $blockedWords)->delete();

        return back()->with('success', "Cleanup complete! $deletedCount old keywords were removed.");
    }
}