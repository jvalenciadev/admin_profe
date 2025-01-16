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
        Schema::create('calificacion_participante', function (Blueprint $table) {
            $table->bigIncrements('cp_id');
            $table->integer('cp_puntaje');
            $table->foreignId('pi_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('pi_id')
                ->on('programa_inscripcion');
            $table->foreignId('pm_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('pm_id')
                ->on('programa_modulo');
            $table->foreignId('pc_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('pc_id')
                ->on('programa_calificacion');
            $table->enum('cp_estado', ['aprobado', 'reprobado', 'baja'])->default('aprobado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificacion_participante');
    }
};
