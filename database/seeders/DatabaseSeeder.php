<?php

// plataforma/database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder principal do banco de dados
 * 
 * Executa todos os seeders necessários para
 * inicializar o sistema
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cria roles e permissions
        $this->call(RoleSeeder::class);

        // Cria usuário super_admin se não existir
        $admin = User::firstOrCreate(
            ['email' => 'admin@matematica.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('admin123'),
            ]
        );
        $admin->assignRole('super_admin');

        $this->command->info('✅ Super Admin criado: admin@matematica.com / admin123');
    }
}
