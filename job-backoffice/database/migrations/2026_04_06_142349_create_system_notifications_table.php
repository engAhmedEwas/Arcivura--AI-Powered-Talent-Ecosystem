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
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('message'); // نص التنبيه
            $table->string('type')->default('info'); // نوع التنبيه (success, danger, info)
            $table->boolean('is_read')->default(false); // هل قرأه الأدمن؟
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
    }
};
