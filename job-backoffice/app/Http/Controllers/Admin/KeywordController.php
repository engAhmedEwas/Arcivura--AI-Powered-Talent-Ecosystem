<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KeywordStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{StoreKeywordRequest, UpdateKeywordRequest, MergeKeywordsRequest};
use App\Models\{Blacklist, Category, Keyword, SystemNotification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\Support\Str;

class KeywordController extends Controller
{
    /**
     * List keywords with dynamic filtering and search.
     */
    public function index(Request $request)
    {
        $query = Keyword::query()->with('categories');

        // Status Filtering
        $status = $request->query('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        } else {
            // الافتراضي عرض المقبول والمعلق فقط في "الكل"
            $query->whereIn('status', [KeywordStatus::APPROVED, KeywordStatus::PENDING]);
        }

        // Search & Category Filtering
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $keywords = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get(); 

        // $affectedRows = Keyword::whereIn('name', $forbiddenTerms)->delete();
        // if ($keywords > 0) {
        //     SystemNotification::create([
        //         'message' => "Global Cleanup: {$keywords} units purged.",
        //         'type' => 'success'
        //     ]);
        // }

        return view('admin.keywords.index', compact('keywords', 'categories'));
    }

    /**
     * Fix 1: تمرير التصنيفات لصفحة الإضافة
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.keywords.create', compact('categories'));
    }

    public function store(StoreKeywordRequest $request)
    {
        try {
            $inputKeywords = $request->input('keywords'); // مصفوفة الكلمات من Select2
            $categoryId = $request->input('category_id');
            $category = Category::findOrFail($categoryId);

            $blacklistedFound = [];
            $validKeywords = [];

            // 1. Blacklist Check
            foreach ($inputKeywords as $name) {
                if (Blacklist::isBlacklisted($name)) {
                    $blacklistedFound[] = $name;
                } else {
                    $validKeywords[] = $name;
                }
            }

            if (!empty($blacklistedFound)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['keywords' => 'Security Violation: Forbidden terms detected: ' . implode(', ', $blacklistedFound)]);
            }

            // 2. Process Valid Keywords
            DB::transaction(function () use ($validKeywords, $category) {
                foreach ($validKeywords as $keywordName) {
                    // تحويل الكلمة لـ Title Case لتوحيد البيانات
                    $keywordName = Str::title(trim($keywordName));

                    $keyword = Keyword::firstOrCreate(
                        ['name' => $keywordName],
                        [
                            'slug' => $this->generateTechnicalSlug($keywordName),
                            'status' => KeywordStatus::PENDING,
                            'is_approved' => false
                        ]
                    );

                    $pivotSlug = $this->generatePivotSlug($category->name, $keyword->name);

                    $keyword->categories()->syncWithoutDetaching([
                        $category->id => ['slug' => $pivotSlug]
                    ]);
                }
            });

            SystemNotification::create([
                'message' => "New Unit Added: " . $request->name,
                'type' => 'info'
            ]);

            return redirect()->route('admin.keywords.index')->with('success', 'Intelligence units ingested and queued for review.');

        } catch (\Exception $e) {
            Log::error("Keyword Ingestion Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Critical System Failure: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Fix 2: تحسين الـ AJAX Update ليتناسب مع الـ Modal
     */
    public function update(UpdateKeywordRequest $request, Keyword $keyword)
    {
        try {
            DB::transaction(function () use ($request, $keyword) {
                $newSlug = $this->generateTechnicalSlug($request->name);

                $keyword->update([
                    'name' => trim($request->name),
                    'slug' => $newSlug
                ]);

                // تحديث الـ Slugs في جميع الروابط المرتبطة (Pivot Table)
                foreach ($keyword->categories as $category) {
                    $pivotSlug = $this->generatePivotSlug($category->name, $keyword->name);
                    $keyword->categories()->updateExistingPivot($category->id, ['slug' => $pivotSlug]);
                }
            });

            SystemNotification::create([
                'message' => "New Unit Updated: " . $request->name,
                'type' => 'info'
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Unit identity re-calibrated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Operation aborted: Registry lock.'], 500);
        }
    }

    /**
     * Merge multiple keywords into a single target keyword.
     */
    public function merge(MergeKeywordsRequest $request)
    {
        $sourceIds = json_decode($request->source_ids);
        $targetId = $request->target_id;

        if (in_array($targetId, $sourceIds)) {
            $sourceIds = array_diff($sourceIds, [$targetId]);
        }

        try {
            DB::transaction(function () use ($sourceIds, $targetId) {
                $target = Keyword::findOrFail($targetId);
                $sources = Keyword::whereIn('id', $sourceIds)->get();

                foreach ($sources as $source) {
                    foreach ($source->categories as $category) {
                        $newPivotSlug = $this->generatePivotSlug($category->name, $target->name);
                        $target->categories()->syncWithoutDetaching([
                            $category->id => ['slug' => $newPivotSlug]
                        ]);
                    }
                    $source->delete();
                }
            });

            return redirect()->route('admin.keywords.index')->with('success', 'Keywords merged successfully!');
            
        } catch (\Exception $e) {
            Log::error("Merge Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Merge failed. Data integrity maintained.');
        }
    }

    /**
     * Toggle approval status.
     */
    public function toggleStatus(Keyword $keyword) 
    { 
        $newStatus = ($keyword->status === KeywordStatus::APPROVED) 
                     ? KeywordStatus::REJECTED 
                     : KeywordStatus::APPROVED;
        
        $keyword->update([
            'status' => $newStatus,
            'is_approved' => ($newStatus === KeywordStatus::APPROVED)
        ]);

        if ($newStatus === KeywordStatus::APPROVED) {
            foreach ($keyword->categories as $category) {
                $category->updateDynamicSlug();
            }
        }

        return response()->json([
            'success' => true,
            'new_status' => $newStatus->value,
            'color_class' => $newStatus->getColor(),
            'label' => $newStatus->getLabel()
        ]);
    }

    /**
     * Handle direct status update (Approved/Rejected) from the index buttons.
     */
    public function updateStatus(Request $request, Keyword $keyword)
    {
        try {
            $status = $request->input('status');

            if (!in_array($status, ['approved', 'rejected'])) {
                return response()->json(['success' => false, 'message' => 'Invalid status protocol.'], 400);
            }

            $keyword->update([
                'status' => $status,
                'is_approved' => ($status === 'approved')
            ]);

            if ($status === 'approved') {
                foreach ($keyword->categories as $category) {
                    if (method_exists($category, 'updateDynamicSlug')) {
                        $category->updateDynamicSlug();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Unit status updated to ' . strtoupper($status),
                'label' => $keyword->status->getLabel(),
                'color_class' => $keyword->status->getColor()
            ]);
        } catch (\Exception $e) {
            Log::error("Status Update Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Registry update failed.'], 500);
        }
    }

    /**
     * Search for Select2 AJAX
     */
    public function search(Request $request)
    {
        $term = $request->query('q');
        if (empty($term)) return response()->json([]);

        $keywords = Keyword::where('name', 'like', "%$term%")
            ->limit(10)
            ->get(['name as text']); // Select2 يتوقع حقل اسمه text

        return response()->json($keywords);
    }

    /**
     * Bluck update
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:keywords,id',
            'action' => 'required|in:approve,blacklist'
        ]);

        try {
            $ids = $request->ids;
            $action = $request->action;

            DB::transaction(function () use ($ids, $action) {
                if ($action === 'approve') {
                    Keyword::whereIn('id', $ids)->update([
                        'status' => KeywordStatus::APPROVED,
                        'is_approved' => true
                    ]);

                    $keywords = Keyword::whereIn('id', $ids)->with('categories')->get();
                    foreach ($keywords as $keyword) {
                        foreach ($keyword->categories as $category) {
                            if (method_exists($category, 'updateDynamicSlug')) {
                                $category->updateDynamicSlug();
                            }
                        }
                    }
                } elseif ($action === 'blacklist') {
                    $keywords = Keyword::whereIn('id', $ids)->get();
                    
                    foreach ($keywords as $keyword) {
                        Blacklist::firstOrCreate(['word' => $keyword->name]);
                        $keyword->delete(); 
                    }
                }
            });

            $message = $action === 'approve' 
                ? 'Selected units have been approved and deployed.' 
                : 'Selected units moved to Blacklist and purged.';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error("Bulk Update Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Bulk operation failed. Check system logs.');
        }
    }

    // --- Private Helper Methods (The Engine) ---

    private function generatePivotSlug($categoryName, $keywordName): string
    {
        $cleanCat = $this->getCleanNameForSlug($categoryName);
        $cleanKey = $this->getCleanNameForSlug($keywordName);
        return Str::slug($cleanCat) . '_' . Str::slug($cleanKey);
    }

    private function getCleanNameForSlug($name): string
    {
        $replacements = ['+' => 'plus', '#' => 'sharp', '@' => 'at', '.' => 'dot'];
        return str_replace(array_keys($replacements), array_values($replacements), $name);
    }

    private function generateTechnicalSlug($name): string
    {
        $cleanName = $this->getCleanNameForSlug($name);
        $slug = Str::slug($cleanName);
        $originalSlug = $slug;
        $count = 1;

        while (Keyword::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        return $slug;
    }

    public function cleanup()
    {
        $forbiddenTerms = Blacklist::pluck('term')->toArray();

        if (empty($forbiddenTerms)) {
            return redirect()->back()->with('info', 'Blacklist is empty. No action taken.');
        }

        $affectedRows = Keyword::whereIn('name', $forbiddenTerms)->delete();
        if ($affectedRows > 0) {
        SystemNotification::create([
            'message' => "Global Cleanup: {$affectedRows} units purged.",
            'type' => 'success'
        ]);
    }

        return redirect()->back()->with('success', "Sanitization Complete: {$affectedRows} legacy units were identified and purged.");
    }

    // Soft delete, force Delete and restore
    public function destroy(Keyword $keyword)
    {
        SystemNotification::create([
            'message' => "Security Update: Term '{$keyword->name}'  has deleted!",
            'type' => 'info'
        ]);
        $keyword->delete();

        return redirect()->back()->with('success', 'Unit has been moved to terminal architecture.');
    }
    
    public function forceDelete($id)
    {
        $keyword = Keyword::onlyTrashed()->findOrFail($id);
        

        SystemNotification::create([
            'message' => "Security Update: Term '{$keyword->name}' has force deleted!",
            'type' => 'info'
        ]);
        $keyword->forceDelete();

        return back()->with('success', 'Keyword permanently purged from the system.');
    }
    
    public function restore($id)
    {
        $keyword = Keyword::onlyTrashed()->findOrFail($id);
        $keyword->restore();
        SystemNotification::create([
            'message' => "Security Update: Term '{$keyword->name} Has been restored",
            'type' => 'success'
        ]);
        return back()->with('success', 'Keyword intelligence restored to active registry.');
    }
}