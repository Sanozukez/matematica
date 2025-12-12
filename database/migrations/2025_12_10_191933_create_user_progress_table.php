<?php

// plataforma/database/migrations/2025_12_10_191933_create_user_progress_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar tabela de progresso do usuário
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignUuid('lesson_id')
                  ->constrained('lessons')
                  ->cascadeOnDelete();
            $table->enum('status', ['not_started', 'in_progress', 'completed'])
                  ->default('not_started');
            $table->unsignedTinyInteger('score')->nullable(); // 0-100
            $table->unsignedInteger('time_spent_seconds')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
