<?php

// plataforma/app/Console/Commands/CleanLessonsData.php

namespace App\Console\Commands;

use App\Domain\Lesson\Models\Lesson;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanLessonsData extends Command
{
    protected $signature = 'lessons:clean-data {--force : Execute without confirmation}';
    protected $description = 'Remove todas as lições (útil após mudança de estrutura de dados)';

    public function handle()
    {
        $this->warn('⚠️  ATENÇÃO: Este comando vai DELETAR PERMANENTEMENTE todas as lições!');
        
        if (!$this->option('force') && !$this->confirm('Tem certeza que deseja continuar?')) {
            $this->info('❌ Operação cancelada.');
            return Command::SUCCESS;
        }

        $this->info('🗑️  Removendo lições...');

        try {
            // Desabilitar foreign keys temporariamente
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Deletar permanentemente todas as lições
            $count = Lesson::withTrashed()->count();
            Lesson::withTrashed()->forceDelete();
            
            // Reabilitar foreign keys
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->info("✅ {$count} lições removidas com sucesso!");
            $this->newLine();
            $this->info('💡 Agora você pode criar novas lições com a estrutura de blocos.');
            
        } catch (\Exception $e) {
            $this->error('❌ Erro ao remover lições: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

