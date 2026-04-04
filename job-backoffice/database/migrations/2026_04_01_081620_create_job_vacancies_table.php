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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->string('location');
            $table->string('type')->default('Full-Time');
            $table->decimal('salary', 12, 3);
            $table->json('requirements')->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->timestamp('closing_date')->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->foreignUuid('company_id')->constrained('companies')->onDelete('restrict');
            $table->foreignUuid('category_id')->constrained('categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
