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
        Schema::create('solicitud_inscripcion_sede', function (Blueprint $table) {
            $table->bigIncrements('sis_id');
            $table->string('sis_ci');
            $table->string('sis_nombre_completo');
            $table->string('sis_celular');
            $table->string('sis_correo');
            $table->string('sis_departamento');
            $table->string('sis_sede');
            $table->text('sis_turno');
            $table->integer('pro_id');
            $table->enum('sis_estado', ['confirmado', 'no confirmado', 'cancelado'])->default('no confirmado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_inscripcion_sede');
    }
};
