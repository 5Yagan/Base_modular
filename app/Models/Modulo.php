<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model
{
    use SoftDeletes;

    protected $table = 'modulos';

    protected $fillable = [
        'nombre',
        'nombre_mostrar',
        'descripcion',
        'icono',
        'esta_activo',
        'roles_disponibles',
        'prefijo_ruta',
        'tiene_usuarios_internos',
        'permisos_por_defecto',
        'orden_mostrar'
    ];

    protected $casts = [
        'esta_activo' => 'boolean',
        'tiene_usuarios_internos' => 'boolean',
        'roles_disponibles' => 'array',
        'permisos_por_defecto' => 'array'
    ];

    // Relaciones
    public function usuariosConAcceso()
    {
        return $this->hasMany(ModuloUsuarioAcceso::class, 'nombre_modulo', 'nombre');
    }

    public function usuariosConRoles()
    {
        return $this->hasMany(ModuloUsuarioRoles::class, 'nombre_modulo', 'nombre');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeOrdenadosPorPrioridad($query)
    {
        return $query->orderBy('orden_mostrar');
    }
}