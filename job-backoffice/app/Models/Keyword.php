<?php

namespace App\Models;

use App\Enums\KeywordStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[fillable(['name', 'slug', 'status', 'is_approved'])]
class Keyword extends Model
{
    use HasUuids, SoftDeletes;
    protected $casts = [
        'status' => KeywordStatus::class,
        'is_approved' => 'boolean',
    ];

    public function categories(): belongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }

    
}
