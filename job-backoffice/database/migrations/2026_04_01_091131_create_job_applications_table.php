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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('ai_score')->index();
            $table->text('ai_feedback');
            $table->enum('status', ['pending', 'reviewed', 'rejected', 'accepted'])->default('pending');
            $table->timestamps();

            $table->softDeletes();

            $table->foreignUuid('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignUuid('job_id')->constrained('job_vacancies')->onDelete('restrict');
            $table->foreignUuid('resume_id')->constrained('resumes')->onDelete('restrict');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
