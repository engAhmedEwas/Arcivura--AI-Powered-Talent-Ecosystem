<?php

namespace App\Enums;

enum KeywordStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getColor(): string {
        return match($this) {
            self::PENDING => 'bg-amber-100 text-amber-800',
            self::APPROVED => 'bg-green-100 text-green-800',
            self::REJECTED => 'bg-red-100 text-red-800',
        };
    }

    public function getLabel(): string {
        return match($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }
}