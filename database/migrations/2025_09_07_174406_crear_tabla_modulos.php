<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();                                              // Laravel obligatorio

            // Campos de negocio
            $table->string('nombre', 100)->unique();                    // Nombre técnico: 'ejem: SistemaBoletas'
            $table->string('nombre_mostrar', 200);                     // Nombre mostrado: 'Ejem: Sistema de Boletas'
            $table->text('descripcion')->nullable();                          // Descripción del módulo
            $table->string('icono', 100)->default('default');   // Icono para el dashboard
            $table->boolean('esta_activo')->default(true);             // ¿Está activo el módulo?
            $table->json('roles_disponibles')->nullable();                   // ['registrador', 'custodio', 'admin']

            // Campos técnicos
            $table->string('prefijo_ruta', 100)->nullable();           // Prefijo de rutas
            $table->boolean('tiene_usuarios_internos')->default(true); // ¿Maneja usuarios internos?
            $table->json('permisos_por_defecto')->nullable();                 // Permisos por defecto
            $table->integer('orden_mostrar')->default(0);              // Orden en el dashboard

            // Laravel obligatorios
            $table->timestamps();                                                     // created_at, updated_at
            $table->softDeletes();                                                    // deleted_at

            // Índices
            $table->index(['esta_activo', 'orden_mostrar']);
            $table->index('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};