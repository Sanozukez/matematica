<?php

// plataforma/database/migrations/2025_12_10_191926_create_badge_dependencies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar tabela de dependências entre badges
 * 
 * Implementa um DAG (Directed Acyclic Graph) para o skill tree
 * badge_id depende de prerequisite_badge_id
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badge_dependencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('badge_id')
                  ->constrained('badges')
                  ->cascadeOnDelete();
            $table->foreignUuid('prerequisite_badge_id')
                  ->constrained('badges')
                  ->cascadeOnDelete();
            $table->timestamps();

            // Uma badge não pode depender dela mesma
            // e cada relação deve ser única
            $table->unique(['badge_id', 'prerequisite_badge_id']);
            
            $table->index('prerequisite_badge_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badge_dependencies');
    }
};
