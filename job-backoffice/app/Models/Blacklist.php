<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['word', 'reason'])]
class Blacklist extends Model
{
    use HasFactory, HasUuids;
    public static function isBlacklisted($word)
    {
        return self::where('word', strtolower(trim($word)))->exists();
    }
}
