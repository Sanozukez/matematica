<?php

// plataforma/database/migrations/2025_12_10_191938_create_user_badges_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar tabela de badges dos usuários
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignUuid('badge_id')
                  ->constrained('badges')
                  ->cascadeOnDelete();
            $table->timestamp('unlocked_at');
            $table->timestamps();

            $table->unique(['user_id', 'badge_id']);
            $table->index('badge_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};
