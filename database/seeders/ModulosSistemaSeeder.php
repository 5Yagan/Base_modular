<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modulo;
use App\Models\User;

class ModulosSistemaSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // 1. Crear módulos base del sistema
        $modulos = [
            [
                'nombre' => 'Users',
                'nombre_mostrar' => 'Gestión de Usuarios',
                'descripcion' => 'CRUD de usuarios del portal con roles y permisos',
                'icono' => 'users',
                'esta_activo' => true,
                'roles_disponibles' => ['viewer', 'editor', 'admin'],
                'prefijo_ruta' => 'users',
                'tiene_usuarios_internos' => true,
                'permisos_por_defecto' => ['view', 'create', 'edit', 'delete'],
                'orden_mostrar' => 1
            ],
            [
                'nombre' => 'Dashboard',
                'nombre_mostrar' => 'Panel Principal',
                'descripcion' => 'Portal principal con vista de módulos disponibles',
                'icono' => 'dashboard',
                'esta_activo' => true,
                'roles_disponibles' => ['viewer'],
                'prefijo_ruta' => 'dashboard',
                'tiene_usuarios_internos' => false,
                'permisos_por_defecto' => ['view'],
                'orden_mostrar' => 0
            ],
            [
                'nombre' => 'SystemManagement',
                'nombre_mostrar' => 'Gestión del Sistema',
                'descripcion' => 'Administración de módulos y asignación de accesos',
                'icono' => 'settings',
                'esta_activo' => true,
                'roles_disponibles' => ['admin'],
                'prefijo_ruta' => 'system-management',
                'tiene_usuarios_internos' => false,
                'permisos_por_defecto' => ['view', 'manage'],
                'orden_mostrar' => 99
            ]
        ];

        foreach ($modulos as $moduloData) {
            Modulo::updateOrCreate(
                ['nombre' => $moduloData['nombre']],
                $moduloData
            );
        }

        // 2. Asignar accesos a usuarios existentes
        $this->asignarAccesosIniciales();

        $this->command->info('✅ Módulos del sistema creados exitosamente');
        $this->command->info('✅ Accesos iniciales asignados');
    }

    private function asignarAccesosIniciales()
    {
        // Buscar usuarios existentes
        $superAdmin = User::where('email', 'admin@sistema.local')->first();
        $sysAdmin = User::where('email', 'sysadmin@sistema.local')->first();
        $basicUser = User::where('email', 'user@sistema.local')->first();

        if ($superAdmin) {
            // SuperAdmin: Acceso total a todos los módulos
            $superAdmin->grantModuleAccess('Users', $superAdmin->id, 'Acceso automático SuperAdmin');
            $superAdmin->assignModuleRole('Users', 'admin', $superAdmin->id, 'Rol automático SuperAdmin');

            $superAdmin->grantModuleAccess('Dashboard', $superAdmin->id, 'Acceso automático SuperAdmin');
            $superAdmin->assignModuleRole('Dashboard', 'viewer', $superAdmin->id, 'Rol automático SuperAdmin');

            $superAdmin->grantModuleAccess('SystemManagement', $superAdmin->id, 'Acceso automático SuperAdmin');
            $superAdmin->assignModuleRole('SystemManagement', 'admin', $superAdmin->id, 'Rol automático SuperAdmin');

            $this->command->info("✅ SuperAdmin: Acceso completo asignado");
        }

        if ($sysAdmin) {
            // SysAdmin: Acceso a gestión de usuarios y dashboard
            $sysAdmin->grantModuleAccess('Users', $superAdmin->id ?? 1, 'Acceso SysAdmin');
            $sysAdmin->assignModuleRole('Users', 'editor', $superAdmin->id ?? 1, 'Rol SysAdmin');

            $sysAdmin->grantModuleAccess('Dashboard', $superAdmin->id ?? 1, 'Acceso SysAdmin');
            $sysAdmin->assignModuleRole('Dashboard', 'viewer', $superAdmin->id ?? 1, 'Rol SysAdmin');

            $this->command->info("✅ SysAdmin: Acceso operativo asignado");
        }

        if ($basicUser) {
            // BasicUser: Solo dashboard
            $basicUser->grantModuleAccess('Dashboard', $superAdmin->id ?? 1, 'Acceso BasicUser');
            $basicUser->assignModuleRole('Dashboard', 'viewer', $superAdmin->id ?? 1, 'Rol BasicUser');

            $this->command->info("✅ BasicUser: Acceso básico asignado");
        }
    }
}