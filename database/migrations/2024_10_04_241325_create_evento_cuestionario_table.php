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
        Schema::create('evento_cuestionario', function (Blueprint $table) {
            $table->bigIncrements('eve_cue_id');
            $table->string('eve_cue_titulo');
            $table->text('eve_cue_descripcion');
            $table->datetime('eve_cue_fecha_ini');
            $table->datetime('eve_cue_fecha_fin');
            $table->enum('eve_cue_estado', ['activo', 'inactivo','eliminado'])->default('activo');
            $table->foreignId('eve_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_id')
                ->on('evento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_cuestionario');
    }
};
