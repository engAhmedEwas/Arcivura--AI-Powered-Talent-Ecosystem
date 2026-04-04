<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KeywordStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Keyword;
use App\Models\User;
use App\Notifications\NewKeywordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // جلب الأقسام مع عد الكلمات المرتبطة بكل منها (Relationship count)
        $categories = Category::withCount('keywords')->latest()->paginate(10);
        
        return view('admin.categories.index', compact('categories'));
    }
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        try{
        if ($request->has('keywords_raw')) {

            $keywordsArray = array_filter(array_map('trim', explode(',', $request->keywords_raw)));
            $request->merge(['keywords' => $keywordsArray]);
        }


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'keywords' => 'required|array',
        ]);


        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);

        $syncData = [];

        $newKeywordsCount = 0;
        $existingKeywordsCount = 0;

        foreach ($validated['keywords'] as $name) {
            $cleanName = trim($name);

            $exists = Keyword::where('name', $cleanName)->exists();
            
            if ($exists) {
                $existingKeywordsCount++;
            } else {
                $newKeywordsCount++;
            }

            $keyword = Keyword::firstOrCreate(
                ['name' => $cleanName],
                [
                    'slug' => Str::slug($cleanName),
                    'status' => KeywordStatus::PENDING, 
                    'is_approved' => false
                ]
            );


            $syncData[$keyword->id] = [
                'slug' => Str::slug($keyword->name) . '_' . Str::slug($category->name)
            ];
        }


        $category->keywords()->sync($syncData);


        $category->load('keywords');
        $category->updateDynamicSlug();

        $admins = User::where('role', [UserRole::COMPANY_ADMIN, UserRole::SUPER_ADMIN])->first();
        if ($admins) {
            $admins->notify(new NewKeywordNotification(count($syncData)));
        }

        if ($existingKeywordsCount > 0 && $newKeywordsCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('warning', "The Category was saved, but there are existing Keywords: $existingKeywordsCount that were simply linked!");
        }
        
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully,  Keywords awaiting admin review!');

        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Thome thing Wrong!' . $e->getMessage())->withInput();
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.update', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if ($request->has('keywords_raw')) {
            $keywordsArray = array_filter(array_map('trim', explode(',', $request->keywords_raw)));
            $request->merge(['keywords' => $keywordsArray]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'keywords' => 'required|array',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['name'])
        ]);

        $syncData = [];

        foreach ($validated['keywords'] as $name) {
            $cleanName = trim($name);

            $keyword = keyword::firstOrCreate(
                ['name' => $cleanName],
                [
                    'slug' => \Illuminate\Support\Str::slug($cleanName),
                    'status' => 'pending',
                    'is_approved' => false
                ]
            );

            $syncData[$keyword->id] = [
                'slug' => \Illuminate\Support\Str::slug($keyword->name) . '_' . \Illuminate\Support\Str::slug($category->name)
            ];
        }

        $category->keywords()->sync($syncData);

        // 6. تحديث الـ Dynamic Slug
        $category->load('keywords');
        $category->updateDynamicSlug();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }

    public function trash()
    {
        $archivedCategories = Category::onlyTrashed()->latest()->paginate(10);
        return view('admin.categories.trash', compact('archivedCategories'));
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.categories.trash')->with('success', 'Category restored successfully!');
    }
}
