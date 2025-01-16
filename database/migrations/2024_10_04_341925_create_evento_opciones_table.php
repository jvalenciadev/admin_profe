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
        Schema::create('evento_opciones', function (Blueprint $table) {
            $table->bigIncrements('eve_opc_id');
            $table->string('eve_opc_texto')->notNullable();
            $table->boolean('eve_opc_es_correcta')->default(false);
            $table->enum('eve_opc_estado', ['activo', 'inactivo','eliminado'])->default('activo');
            $table->foreignId('eve_pre_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_pre_id')
                ->on('evento_pregunta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_opciones');
    }
};
