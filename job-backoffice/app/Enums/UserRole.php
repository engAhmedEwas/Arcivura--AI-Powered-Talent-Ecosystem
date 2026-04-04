<?php

namespace App\Enums;
/**
 * 💡 UserRole Enum
 * Defines the strict roles for the Arcivura Ecosystem.
 * Used for RBAC (Role-Based Access Control).
 */
enum UserRole : string
{
    case SUPER_ADMIN         = 'super_admin';
    case COMPANY_ADMIN       = 'company_admin';
    case MODERATOR           = 'moderator';
    case OWNER               = 'company_owner';
    case SEEKER              = 'job_seeker';

    public function lable(): string
    {
        return match ($this) {
            self::SUPER_ADMIN         => 'System Master',
            self::COMPANY_ADMIN       => 'Employer Manager',
            self::MODERATOR           => 'Moderator',
            self::OWNER               => 'Company Owner',
            self::SEEKER              => 'Job Seeker',
        };
    }
}