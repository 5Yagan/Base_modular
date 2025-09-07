<?php

namespace App\Models\Traits;

use App\Models\Modulo;
use App\Models\ModuloUsuarioAcceso;
use App\Models\ModuloUsuarioRoles;

trait HasModuleAccess
{
    /**
     * Verificar si el usuario tiene acceso a un módulo específico
     */
    public function hasAccessToModule(string $nombreModulo): bool
    {
        return $this->modulosAcceso()
            ->where('nombre_modulo', $nombreModulo)
            ->where('tiene_acceso', true)
            ->where(function($query) {
                $query->whereNull('acceso_expira_en')
                    ->orWhere('acceso_expira_en', '>', now());
            })
            ->exists();
    }

    /**
     * Obtener el rol del usuario dentro de un módulo específico
     */
    public function getRoleInModule(string $nombreModulo): ?string
    {
        $role = $this->modulosRoles()
            ->where('nombre_modulo', $nombreModulo)
            ->where('esta_activo', true)
            ->where(function($query) {
                $query->whereNull('rol_expira_en')
                    ->orWhere('rol_expira_en', '>', now());
            })
            ->first();

        return $role?->rol_en_modulo;
    }

    /**
     * Verificar si el usuario tiene un rol específico en un módulo
     */
    public function hasRoleInModule(string $nombreModulo, string $rol): bool
    {
        return $this->getRoleInModule($nombreModulo) === $rol;
    }

    /**
     * Verificar si el usuario puede realizar una acción en un módulo
     * basado en jerarquía de roles
     */
    public function canPerformInModule(string $nombreModulo, string $requiredRole): bool
    {
        if (!$this->hasAccessToModule($nombreModulo)) {
            return false;
        }

        $userRole = $this->getRoleInModule($nombreModulo);

        if (!$userRole) {
            return false;
        }

        return $this->roleHasPermission($userRole, $requiredRole);
    }

    /**
     * Obtener todos los módulos a los que el usuario tiene acceso
     */
    public function getAccessibleModules()
    {
        return Modulo::whereIn('nombre',
            $this->modulosAcceso()
                ->where('tiene_acceso', true)
                ->where(function($query) {
                    $query->whereNull('acceso_expira_en')
                        ->orWhere('acceso_expira_en', '>', now());
                })
                ->pluck('nombre_modulo')
        )
            ->where('esta_activo', true)
            ->orderBy('orden_mostrar')
            ->get();
    }

    /**
     * Obtener información completa de módulos con roles
     */
    public function getModulesWithRoles()
    {
        return $this->getAccessibleModules()->map(function($modulo) {
            $modulo->user_role = $this->getRoleInModule($modulo->nombre);
            return $modulo;
        });
    }

    /**
     * Relación: Accesos a módulos
     */
    public function modulosAcceso()
    {
        return $this->hasMany(ModuloUsuarioAcceso::class, 'user_id');
    }

    /**
     * Relación: Roles en módulos
     */
    public function modulosRoles()
    {
        return $this->hasMany(ModuloUsuarioRoles::class, 'user_id');
    }

    /**
     * Verificar jerarquía de roles
     */
    private function roleHasPermission(string $userRole, string $requiredRole): bool
    {
        $hierarchy = [
            'viewer' => 1,
            'editor' => 2,
            'admin' => 3
        ];

        $userLevel = $hierarchy[$userRole] ?? 0;
        $requiredLevel = $hierarchy[$requiredRole] ?? 999;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Asignar acceso a un módulo
     */
    public function grantModuleAccess(string $nombreModulo, int $asignadoPor, ?string $notas = null)
    {
        return ModuloUsuarioAcceso::updateOrCreate(
            [
                'user_id' => $this->id,
                'nombre_modulo' => $nombreModulo
            ],
            [
                'tiene_acceso' => true,
                'acceso_otorgado_en' => now(),
                'asignado_por' => $asignadoPor,
                'notas' => $notas
            ]
        );
    }

    /**
     * Asignar rol en un módulo
     */
    public function assignModuleRole(string $nombreModulo, string $rol, int $asignadoPor, ?string $notas = null)
    {
        return ModuloUsuarioRoles::updateOrCreate(
            [
                'user_id' => $this->id,
                'nombre_modulo' => $nombreModulo
            ],
            [
                'rol_en_modulo' => $rol,
                'esta_activo' => true,
                'rol_asignado_en' => now(),
                'asignado_por' => $asignadoPor,
                'notas_asignacion' => $notas
            ]
        );
    }

    /**
     * Revocar acceso a un módulo
     */
    public function revokeModuleAccess(string $nombreModulo)
    {
        $this->modulosAcceso()
            ->where('nombre_modulo', $nombreModulo)
            ->update(['tiene_acceso' => false]);

        $this->modulosRoles()
            ->where('nombre_modulo', $nombreModulo)
            ->update(['esta_activo' => false]);
    }
}