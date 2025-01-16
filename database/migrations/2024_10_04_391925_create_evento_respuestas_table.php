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
        Schema::create('evento_respuestas', function (Blueprint $table) {
            $table->bigIncrements('eve_res_id');
            $table->string('eve_res_texto');
            $table->foreignId('eve_pre_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_pre_id')
                ->on('evento_pregunta');
            $table->foreignId('eve_opc_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_opc_id')
                ->on('evento_opciones');
            $table->foreignId('eve_per_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_per_id')
                ->on('evento_personas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_respuestas');
    }
};
