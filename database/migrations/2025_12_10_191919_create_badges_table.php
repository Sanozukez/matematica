<?php

// plataforma/database/migrations/2025_12_10_191919_create_badges_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar tabela de badges (conquistas)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')
                  ->nullable()
                  ->constrained('modules')
                  ->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('color', 7)->nullable(); // Hex color
            $table->unsignedInteger('points')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('module_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
