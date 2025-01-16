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
        Schema::create('evento_personas', function (Blueprint $table) {
            $table->bigIncrements('eve_per_id');
            $table->bigInteger('eve_per_ci');
            $table->string('eve_per_complemento');
            $table->string('eve_per_expedido');
            $table->string('eve_per_nombre_1');
            $table->string('eve_per_nombre_2');
            $table->string('eve_per_apellido_1');
            $table->string('eve_per_apellido_2');
            $table->date('eve_per_fecha_nacimiento');
            $table->string('eve_per_correo');
            $table->string('eve_per_celular');
            $table->foreignId('gen_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('gen_id')
                ->on('genero');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_personas');
    }
};
