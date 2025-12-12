<?php

// plataforma/database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder para criar os roles e permissions iniciais
 * 
 * Roles:
 * - super_admin: Acesso total ao sistema
 * - creator: Pode criar e gerenciar seus próprios cursos
 * - student: Pode consumir cursos e ganhar badges
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ===== PERMISSIONS =====

        // Cursos
        $coursePermissions = [
            'view_any_course',
            'view_course',
            'create_course',
            'update_course',
            'delete_course',
            'delete_any_course',
            'restore_course',
            'restore_any_course',
            'force_delete_course',
            'force_delete_any_course',
        ];

        // Módulos
        $modulePermissions = [
            'view_any_module',
            'view_module',
            'create_module',
            'update_module',
            'delete_module',
            'delete_any_module',
        ];

        // Lições
        $lessonPermissions = [
            'view_any_lesson',
            'view_lesson',
            'create_lesson',
            'update_lesson',
            'delete_lesson',
            'delete_any_lesson',
        ];

        // Badges
        $badgePermissions = [
            'view_any_badge',
            'view_badge',
            'create_badge',
            'update_badge',
            'delete_badge',
            'delete_any_badge',
        ];

        // Usuários (apenas super_admin)
        $userPermissions = [
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'delete_any_user',
        ];

        // Roles (apenas super_admin)
        $rolePermissions = [
            'view_any_role',
            'view_role',
            'create_role',
            'update_role',
            'delete_role',
        ];

        // Cria todas as permissions
        $allPermissions = array_merge(
            $coursePermissions,
            $modulePermissions,
            $lessonPermissions,
            $badgePermissions,
            $userPermissions,
            $rolePermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ===== ROLES =====

        // Super Admin - Acesso total
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // Creator - Gerencia cursos (sem acesso a usuários/roles)
        $creator = Role::firstOrCreate(['name' => 'creator', 'guard_name' => 'web']);
        $creator->syncPermissions(array_merge(
            $coursePermissions,
            $modulePermissions,
            $lessonPermissions,
            $badgePermissions
        ));

        // Student - Apenas visualização (futuro: área do aluno)
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        // Students não têm permissões no painel admin
    }
}

