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
        Schema::create('category_keyword', function (Blueprint $table) {
            $table->foreignUuid('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignUuid('keyword_id')->constrained('keywords')->onDelete('cascade');
            $table->string('slug')->nullable()->unique();

            $table->primary(['category_id', 'keyword_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_keyword');
    }
};
