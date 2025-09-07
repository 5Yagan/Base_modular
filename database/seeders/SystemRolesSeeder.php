<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SystemRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ================================================================
        // CREAR PERMISOS DEL SISTEMA (ADMINISTRATIVOS)
        // ================================================================

        $systemPermissions = [
            // Gestión de usuarios
            'system.users.create',
            'system.users.edit',
            'system.users.delete',
            'system.users.view',

            // Gestión de roles
            'system.roles.assign',
            'system.roles.view',

            // Gestión de módulos
            'system.modules.manage',
            'system.modules.install',
            'system.modules.configure',

            // Sistema
            'system.logs.view',
            'system.config.manage',
        ];

        foreach ($systemPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ================================================================
        // CREAR PERMISOS DE MÓDULOS (DINÁMICOS)
        // ================================================================

        $modulePermissions = [
            'module.users.access',  // Para el módulo Users existente
            // Los futuros módulos crearán sus permisos automáticamente
        ];

        foreach ($modulePermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ================================================================
        // CREAR ROLES
        // ================================================================

        // SuperAdmin - Acceso total
        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $superAdmin->syncPermissions(Permission::all()); // Todos los permisos

        // Sysadmin - Administrador operativo
        $sysAdmin = Role::firstOrCreate(['name' => 'Sysadmin']);
        $sysAdminPermissions = [
            'system.users.create',
            'system.users.edit',
            'system.users.view',
            'system.roles.assign',
            'system.roles.view',
            'system.modules.configure',
            'system.logs.view',
            'module.users.access',
        ];
        $sysAdmin->syncPermissions($sysAdminPermissions);

        // BasicUser - Usuario final (sin permisos administrativos)
        $basicUser = Role::firstOrCreate(['name' => 'BasicUser']);
        // BasicUser no tiene permisos por defecto, se asignan manualmente según necesidades

        // ================================================================
        // CREAR USUARIO SUPERADMIN INICIAL
        // ================================================================

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sistema.local'],
            [
                'name' => 'Super Administrador',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol SuperAdmin
        $adminUser->assignRole('SuperAdmin');

        // ================================================================
        // CREAR USUARIOS DE PRUEBA (OPCIONAL)
        // ================================================================

        // Usuario Sysadmin
        $sysAdminUser = User::firstOrCreate(
            ['email' => 'sysadmin@sistema.local'],
            [
                'name' => 'Administrador Sistema',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $sysAdminUser->assignRole('Sysadmin');

        // Usuario BasicUser
        $basicTestUser = User::firstOrCreate(
            ['email' => 'user@sistema.local'],
            [
                'name' => 'Usuario Básico',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $basicTestUser->assignRole('BasicUser');
        // Darle acceso al módulo Users para pruebas
        $basicTestUser->givePermissionTo('module.users.access');

        // ================================================================
        // OUTPUT DE CONFIRMACIÓN
        // ================================================================

        $this->command->info('✅ Sistema de roles y permisos creado exitosamente:');
        $this->command->info('   - 3 roles: SuperAdmin, Sysadmin, BasicUser');
        $this->command->info('   - ' . count($systemPermissions) . ' permisos del sistema');
        $this->command->info('   - ' . count($modulePermissions) . ' permisos de módulos');
        $this->command->info('   - 3 usuarios de prueba creados');
        $this->command->info('');
        $this->command->info('🔑 Credenciales de acceso:');
        $this->command->info('   SuperAdmin: admin@sistema.local / password123');
        $this->command->info('   Sysadmin:   sysadmin@sistema.local / password123');
        $this->command->info('   BasicUser:  user@sistema.local / password123');
    }
}