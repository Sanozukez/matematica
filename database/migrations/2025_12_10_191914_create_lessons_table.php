<?php

// plataforma/database/migrations/2025_12_10_191914_create_lessons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar tabela de lições
 * 
 * A coluna 'content' é do tipo JSON e armazena a estrutura do Editor.js
 * NUNCA salva HTML renderizado, apenas a estrutura JSON limpa dos blocos
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')
                  ->constrained('modules')
                  ->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->enum('type', ['text', 'video', 'quiz', 'game'])
                  ->default('text');
            // JSON puro do Editor.js (não HTML renderizado)
            $table->json('content')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->unsignedSmallInteger('duration_minutes')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['module_id', 'slug']);
            $table->index(['module_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
