<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KeywordStatus;
use App\Http\Controllers\Controller;
use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pendingKeywords = Keyword::where('status', KeywordStatus::PENDING)
            ->with('categories') 
            ->latest()
            ->paginate(10)
        ;
        return view('admin.keywords.pending', compact('pendingKeywords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    
    public function approve(Keyword $keyword)
    {
        // 1. تحديث الحالة
        $keyword->update([
            'status' => KeywordStatus::APPROVED,
            'is_approved' => true
        ]);

        // 2. تحديث الـ Slug لكل قسم مرتبط بهذه الكلمة ليعكس التغيير الجديد
        foreach ($keyword->categories as $category) {
            $category->updateDynamicSlug();
        }

        return back()->with('success', 'Keyword Approved');
    }

    
    public function reject(Keyword $keyword)
    {
        $keyword->update([
            'status' => KeywordStatus::REJECTED,
            'is_approved' => false
        ]);

        return back()->with('error', 'Keyword Rejected');
    }
}

