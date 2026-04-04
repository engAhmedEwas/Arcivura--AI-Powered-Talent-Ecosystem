<?php

    namespace App\Models;

    /**
     * 🚀 Arcivura: AI-Powered Talent Ecosystem
     * Initial MVP Release - Project Kickoff
     * * This model serves as the primary entity for the Talent Ecosystem.
     * Developed by Ahmed Ewas with focus on Architectural Integrity & Scalability.
     * * @version 0.1.0 (Initial Architectural Phase)
     * @date 2026-03
    */


    // use Illuminate\Contracts\Auth\MustVerifyEmail;
    use Database\Factories\UserFactory;
    use Illuminate\Database\Eloquent\Attributes\Fillable;
    use Illuminate\Database\Eloquent\Attributes\Hidden;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Database\Eloquent\Concerns\HasUuids;
    use app\Enums\UserRole;

    #[Fillable(['name', 'email', 'password', 'role', 'otp_code',
    'otp_expires_at', 'is_verified', 'theme', 'last_login_at'])]
    #[Hidden(['password', 'remember_token'])]
    class User extends Authenticatable
    {
        /** @use HasFactory<UserFactory> */
        use HasFactory, Notifiable, HasUuids;

        /**
         * Get the attributes that should be cast.
         *
         * @return array<string, string>
         */
        protected function casts(): array
        {
            return [
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
                'role' => UserRole::class,
                'otp_expires_at' => 'datetime',
                'last_login_at' => 'datetime',
                'is_verified' => 'boolean',
            ];
        }
    }




