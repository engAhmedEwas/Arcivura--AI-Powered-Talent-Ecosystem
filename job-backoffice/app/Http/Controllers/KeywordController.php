<?php

namespace App\Http\Controllers;

use App\Models\keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(keyword::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:keywords,name',
        ]);

        $keyword = keyword::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);

        return response()->json($keyword, 201);
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
}


/*
{
  019d52e9-ea28-715c-b9b3-521cf8a7e42b  ,
  019d52eb-be61-735c-b99b-86183aca9c4f
}

{
    name: Graphic Design
    keyword1: UI
    keyword2: UX

    slug1: UI_Graphic Design
    siug2: UX_Graphic Design
}
*/