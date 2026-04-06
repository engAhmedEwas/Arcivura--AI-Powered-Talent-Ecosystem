<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            
            $table->uuid('id')->primary();
            $table->string('action');
            $table->string('ip_address');
            $table->string('user_agent'); // ??
            $table->json('payload'); // ??
            $table->timestamps();

            $table->foreignUuid('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignUuid('keyword_id')->constrained('keywords')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
