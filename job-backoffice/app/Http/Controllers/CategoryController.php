<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Keyword;
use App\Enums\KeywordStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        // 1. التحقق من المدخلات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'keywords' => 'required|array',
        ]);

        // 2. إنشاء القسم (Category)
        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);

        $syncData = [];

        // 3. معالجة الكلمات بنظام المراجعة اليدوية
        foreach ($validated['keywords'] as $name) {
            $cleanName = trim($name);

            // استخدام firstOrCreate لضمان عدم تكرار الكلمة
            $keyword = Keyword::firstOrCreate(
                ['name' => $cleanName],
                [
                    'slug' => Str::slug($cleanName),
                    'status' => KeywordStatus::PENDING, // استخدام الـ Enum
                    'is_approved' => false
                ]
            );

            // تجهيز بيانات الـ Pivot مع الـ Slug المركب
            $syncData[$keyword->id] = [
                'slug' => Str::slug($keyword->name) . '_' . Str::slug($category->name)
            ];
        }

        // 4. ربط الكلمات بالقسم (Sync)
        $category->keywords()->sync($syncData);

        // 5. تطبيق نظام الـ Dynamic Slug الخاص بك
        $category->load('keywords');
        $category->updateDynamicSlug();

        // 6. الرد النهائي
        return response()->json([
            'message' => 'Processed successfully. Keywords awaiting admin review.',
            'data' => $category->fresh('keywords')
        ], 201);
    }
}