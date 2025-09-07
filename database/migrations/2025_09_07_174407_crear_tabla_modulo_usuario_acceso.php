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
        Schema::create('modulo_usuario_acceso', function (Blueprint $table) {
            $table->id(); // Laravel obligatorio

            // Relaciones
            $table->unsignedBigInteger('user_id'); // Laravel convención
            $table->string('nombre_modulo', 100); // Referencia al nombre del módulo

            // Campos de negocio
            $table->boolean('tiene_acceso')->default(true); // ¿Tiene acceso?
            $table->text('notas')->nullable(); // Notas sobre el acceso

            // Campos técnicos
            $table->timestamp('acceso_otorgado_en')->useCurrent(); // Cuándo se otorgó
            $table->timestamp('acceso_expira_en')->nullable(); // Si el acceso expira
            $table->unsignedBigInteger('asignado_por'); // Quién asignó

            // Laravel obligatorios
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at

            // Índices y constraints
            $table->unique(['user_id', 'nombre_modulo']); // Un registro por usuario/módulo
            $table->index(['nombre_modulo', 'tiene_acceso']);
            $table->index(['user_id', 'tiene_acceso']);

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
        Schema::dropIfExists('modulo_usuario_acceso');
    }
};
