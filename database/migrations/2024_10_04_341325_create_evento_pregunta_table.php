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
        Schema::create('evento_pregunta', function (Blueprint $table) {
            $table->bigIncrements('eve_pre_id');
            $table->string('eve_pre_texto');
            $table->enum('eve_pre_tipo', ['multiple_choice', 'respuesta_abierta', 'booleano'])->notNullable();
            $table->boolean('eve_pre_obligatorio')->default(true);
            $table->string('eve_pre_respuesta_correcta');
            $table->enum('eve_pre_estado', ['activo', 'inactivo','eliminado'])->default('activo');
            $table->foreignId('eve_cue_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_cue_id')
                ->on('evento_cuestionario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_pregunta');
    }
};
