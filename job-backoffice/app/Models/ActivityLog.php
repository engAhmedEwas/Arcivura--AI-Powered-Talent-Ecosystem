<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['action', 'ip_address','user_agent','payload'])]
class ActivityLog extends Model
{
    use HasFactory, HasUuids;

    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // داخل موديل ActivityLog
    public static function log($action, $keywordId = null, $description = null)
    {
        self::create([
            // 'user_id' => auth()->id(),
            'keyword_id' => $keywordId,
            'action' => $action, // مثلاً: 'approved', 'rejected', 'updated'
            'description' => $description,
        ]);
    }
}
