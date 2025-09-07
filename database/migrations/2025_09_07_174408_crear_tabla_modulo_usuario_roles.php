<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modulo_usuario_roles', function (Blueprint $table) {
            $table->id(); // Laravel obligatorio

            // Relaciones
            $table->unsignedBigInteger('user_id'); // Laravel convención
            $table->string('nombre_modulo', 100); // Referencia al nombre del módulo

            // Campos de negocio
            $table->string('rol_en_modulo', 50); // 'registrador', 'custodio', 'admin'
            $table->text('notas_asignacion')->nullable(); // Notas sobre la asignación

            // Campos técnicos
            $table->boolean('esta_activo')->default(true); // ¿Está activo?
            $table->timestamp('rol_asignado_en')->useCurrent(); // Cuándo se asignó
            $table->timestamp('rol_expira_en')->nullable(); // Si el rol expira
            $table->unsignedBigInteger('asignado_por'); // Quién asignó

            // Laravel obligatorios
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at

            // Índices y constraints
            $table->unique(['user_id', 'nombre_modulo']); // Un rol por usuario/módulo
            $table->index(['nombre_modulo', 'rol_en_modulo']);
            $table->index(['user_id', 'esta_activo']);
            $table->index(['rol_en_modulo', 'esta_activo']);

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('asignado_por')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('nombre_modulo')->references('nombre')->on('modulos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulo_usuario_roles');
    }
};