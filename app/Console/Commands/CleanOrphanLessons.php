<?php

// plataforma/app/Console/Commands/CleanOrphanLessons.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Comando para limpar lições órfãs ou soft deleted
 */
class CleanOrphanLessons extends Command
{
    protected $signature = 'lessons:clean-orphans 
                            {--force : Force delete all trashed lessons}
                            {--hard : Hard delete (permanent removal)}
                            {--slug= : Clean specific slug}';
    protected $description = 'Remove lições órfãs e soft deleted';

    public function handle()
    {
        $this->info('🔍 Verificando lições...');

        // 1. Verificar soft deleted
        $trashedCount = DB::table('lessons')
            ->whereNotNull('deleted_at')
            ->count();

        if ($trashedCount > 0) {
            $this->warn("📦 Encontradas {$trashedCount} lições soft deleted");
            
            $trashed = DB::table('lessons')
                ->whereNotNull('deleted_at')
                ->get(['id', 'title', 'slug', 'module_id', 'deleted_at']);

            foreach ($trashed as $lesson) {
                $this->line("  - {$lesson->title} ({$lesson->slug}) - Deletada em: {$lesson->deleted_at}");
            }

            if ($this->option('force') || $this->confirm('Remover permanentemente essas lições?', false)) {
                if ($this->option('hard')) {
                    // Hard delete - remove permanentemente do banco
                    DB::table('lessons')->whereNotNull('deleted_at')->delete();
                    $this->info("✅ {$trashedCount} lições removidas PERMANENTEMENTE (hard delete)");
                } else {
                    // Apenas remove do soft delete
                    DB::table('lessons')->whereNotNull('deleted_at')->delete();
                    $this->info("✅ {$trashedCount} lições removidas");
                }
            }
        }

        // 2. Verificar lições ativas
        $activeCount = DB::table('lessons')
            ->whereNull('deleted_at')
            ->count();

        if ($activeCount > 0) {
            $this->info("📚 Lições ativas: {$activeCount}");
            
            $lessons = DB::table('lessons')
                ->whereNull('deleted_at')
                ->get(['id', 'title', 'slug', 'module_id', 'created_at']);

            foreach ($lessons as $lesson) {
                $this->line("  - {$lesson->title} ({$lesson->slug}) - Módulo: {$lesson->module_id}");
            }
        }

        // 3. Verificar lições com o slug específico (se informado)
        if ($slug = $this->option('slug')) {
            $specificLessons = DB::table('lessons')
                ->where('slug', $slug)
                ->get(['id', 'title', 'slug', 'module_id', 'deleted_at']);

            if ($specificLessons->isNotEmpty()) {
                $this->warn("🔍 Lições com slug '{$slug}':");
                foreach ($specificLessons as $lesson) {
                    $status = $lesson->deleted_at ? "DELETADA ({$lesson->deleted_at})" : "ATIVA";
                    $this->line("  - ID: {$lesson->id}, Módulo: {$lesson->module_id}, Status: {$status}");
                }

                if ($this->option('force') || $this->confirm("Remover TODAS as lições com slug '{$slug}'?", false)) {
                    DB::table('lessons')->where('slug', $slug)->delete();
                    $this->info("✅ Lições com slug '{$slug}' removidas");
                }
            } else {
                $this->info("✅ Nenhuma lição encontrada com slug '{$slug}'");
            }
        }

        if ($trashedCount === 0 && $activeCount === 0) {
            $this->info('✅ Nenhuma lição encontrada no banco!');
        }

        return 0;
    }
}

