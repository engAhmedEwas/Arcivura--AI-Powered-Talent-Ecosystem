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
        Schema::create('resumes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('file_name');
            $table->string('file_uri')->index();
            $table->json('parsed_data'); // ??
            $table->enum('status', ['processing', 'ready', 'error'])->default('processing');
            $table->timestamps();

            $table->softDeletes();

            $table->foreignUuid('user_id')->constained('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
