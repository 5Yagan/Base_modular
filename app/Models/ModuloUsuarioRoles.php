<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloUsuarioRoles extends Model
{
    use  SoftDeletes;

    protected $table = 'modulo_usuario_roles';

    protected $fillable = [
        'user_id',
        'nombre_modulo',
        'rol_en_modulo',
        'esta_activo',
        'rol_asignado_en',
        'rol_expira_en',
        'asignado_por',
        'notas_asignacion'
    ];

    protected $casts = [
        'esta_activo' => 'boolean',
        'rol_asignado_en' => 'datetime',
        'rol_expira_en' => 'datetime'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'nombre_modulo', 'nombre');
    }

    public function asignadoPor()
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true)
            ->where(function($q) {
                $q->whereNull('rol_expira_en')
                    ->orWhere('rol_expira_en', '>', now());
            });
    }

    public function scopePorModulo($query, $nombreModulo)
    {
        return $query->where('nombre_modulo', $nombreModulo);
    }

    public function scopePorRol($query, $rol)
    {
        return $query->where('rol_en_modulo', $rol);
    }
}