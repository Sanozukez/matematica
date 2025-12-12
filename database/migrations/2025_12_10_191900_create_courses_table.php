<?php

// plataforma/database/migrations/2025_12_10_191900_create_courses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar tabela de cursos
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('level', ['basic', 'fundamental', 'medium', 'advanced'])
                  ->default('basic');
            $table->string('icon', 50)->nullable();
            $table->string('color', 7)->nullable(); // Hex color
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_gamified')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['level', 'is_active']);
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
