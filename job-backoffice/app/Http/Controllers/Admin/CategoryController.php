<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('keywords')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);
            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            Log::error("Error storing category: " . $e->getMessage());
            return back()->with('error', 'Something went wrong.')->withInput();
        }
    }

    // دالة التحديث المخصصة للـ AJAX
    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id
        ]);

        try {
            $category->update([
                'name' => $request->name,
                'slug' => \Illuminate\Support\Str::slug($request->name)
            ]);

            return response()->json([
                'success' => true,
                'new_slug' => $category->slug
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->keywords()->count() > 0) {
                return back()->with('error', 'Cannot delete: Category has linked keywords.');
            }
            $category->delete();
            return back()->with('success', 'Category moved to trash.');
        } catch (\Exception $e) {
            return back()->with('error', 'Action failed.');
        }
    }

    public function trash()
    {
        $archivedCategories = Category::onlyTrashed()->latest()->paginate(10);
        $archivedKeywords = Keyword::onlyTrashed()->latest()->paginate(10);
        return view('admin.categories.trash', compact('archivedCategories', 'archivedKeywords'));
    }

    public function restore($id)
    {
        try {
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->restore();
            return redirect()->route('admin.categories.trash')->with('success', 'Restored.');
        } catch (\Exception $e) {
            return back()->with('error', 'Restore failed.');
        }
    }
}