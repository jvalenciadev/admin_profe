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
        Schema::create('evento_restriccion', function (Blueprint $table) {
            $table->bigIncrements('er_id');
            $table->text('er_descripcion');
            $table->json('gen_ids')->nullable()->default(null);
            $table->json('sub_ids')->nullable()->default(null);
            $table->json('niv_ids')->nullable()->default(null);
            $table->json('esp_ids')->nullable()->default(null);
            $table->json('esp_nombres')->nullable()->default(null);
            $table->json('cat_ids')->nullable()->default(null);
            $table->json('car_ids')->nullable()->default(null);
            $table->json('car_nombres')->nullable()->default(null);
            $table->boolean('per_funcion')->nullable()->default(null);
            $table->enum('pr_estado', ['activo', 'inactivo','eliminado'])->default('activo');
            $table->foreignId('eve_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('eve_id')
                ->on('evento');
            $table->timestamps();
            // Añadir la restricción única
            $table->unique('eve_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_restriccion');
    }
};
