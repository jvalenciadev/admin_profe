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
        Schema::create('evento_inscripcion_v2', function (Blueprint $table) {
            $table->bigIncrements('eve_ins_id');
            $table->boolean('eve_ins_asistencia')->default(false);
            $table->enum('eve_ins_estado', ['activo', 'inactivo', 'eliminado'])->default('activo');
            $table->foreignId('eve_per_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_per_id')
                ->on('evento_personas');
            $table->foreignId('eve_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_id')
                ->on('evento');
            $table->foreignId('dep_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('dep_id')
                ->on('departamento');
            $table->foreignId('pm_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('pm_id')
                ->on('programa_modalidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_inscripcion_v2');
    }
};
