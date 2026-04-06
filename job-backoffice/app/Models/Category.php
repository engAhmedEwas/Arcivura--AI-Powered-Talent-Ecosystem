<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    // Caregory Has Many job_vacancies (1:M)
    public function job_vacancies(): HasMany
    {
       return $this->hasMany(JobVacancy::class);
    }

    // Caregory Has Many keywords (M:N)
    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class)->withPivot('slug')->withTimestamps();
    }

    /**
      * 🧠 Dynamic Slug Logic for Laravel 13
    */
    public function updateDynamicSlug(): void
    {
        // جلب أول كلمة مفتاحية معتمدة فقط لربطها بالاسم
        // أو إذا كنت تريد عمل Slug فريد لكل ارتباط، فهذا مكانه الجدول الوسيط (Pivot)
        $firstKeyword = $this->keywords()
            ->where('is_approved', true)
            ->first();

        $nameSlug = Str::slug($this->name);
        $shortId = substr($this->id, 0, 5);

        if ($firstKeyword) {
            // النتيجة: keyword-name_category-name
            $categorySlug = Str::slug($firstKeyword->name) . '_' . $nameSlug;
        } else {
            // في حال عدم وجود كلمات معتمدة
            $categorySlug = $nameSlug . '_' . $shortId;
        }

        $this->update(['slug' => $categorySlug]);
    }
        
}
