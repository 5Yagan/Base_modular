<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloUsuarioAcceso extends Model
{
    use SoftDeletes;

    protected $table = 'modulo_usuario_acceso';

    protected $fillable = [
        'user_id',
        'nombre_modulo',
        'tiene_acceso',
        'notas',
        'acceso_otorgado_en',
        'acceso_expira_en',
        'asignado_por'
    ];

    protected $casts = [
        'tiene_acceso' => 'boolean',
        'acceso_otorgado_en' => 'datetime',
        'acceso_expira_en' => 'datetime'
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
    public function scopeConAcceso($query)
    {
        return $query->where('tiene_acceso', true);
    }

    public function scopeActivos($query)
    {
        return $query->whereNull('acceso_expira_en')
            ->orWhere('acceso_expira_en', '>', now());
    }
}