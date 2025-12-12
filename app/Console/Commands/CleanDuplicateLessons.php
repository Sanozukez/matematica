<?php

// plataforma/app/Console/Commands/CleanDuplicateLessons.php

namespace App\Console\Commands;

use App\Domain\Lesson\Models\Lesson;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Comando para limpar lições duplicadas
 */
class CleanDuplicateLessons extends Command
{
    protected $signature = 'lessons:clean-duplicates';
    protected $description = 'Remove lições duplicadas mantendo apenas a mais recente';

    public function handle()
    {
        $this->info('Procurando lições duplicadas...');

        // Busca duplicatas (mesmo module_id + slug)
        $duplicates = DB::table('lessons')
            ->select('module_id', 'slug', DB::raw('COUNT(*) as count'), DB::raw('MIN(created_at) as oldest'))
            ->whereNull('deleted_at')
            ->groupBy('module_id', 'slug')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('✅ Nenhuma duplicata encontrada!');
            return 0;
        }

        $this->warn("Encontradas {$duplicates->count()} duplicatas:");

        foreach ($duplicates as $duplicate) {
            $this->line("  - Módulo: {$duplicate->module_id}, Slug: {$duplicate->slug} ({$duplicate->count} registros)");

            // Busca todas as lições duplicadas
            $lessons = Lesson::where('module_id', $duplicate->module_id)
                ->where('slug', $duplicate->slug)
                ->orderBy('created_at', 'desc')
                ->get();

            // Mantém a mais recente, remove as antigas
            $keep = $lessons->first();
            $toDelete = $lessons->slice(1);

            $this->line("    Mantendo: {$keep->title} (ID: {$keep->id})");

            foreach ($toDelete as $lesson) {
                $this->line("    Removendo: {$lesson->title} (ID: {$lesson->id})");
                $lesson->forceDelete(); // Force delete para remover permanentemente
            }
        }

        $this->info('✅ Limpeza concluída!');
        return 0;
    }
}

