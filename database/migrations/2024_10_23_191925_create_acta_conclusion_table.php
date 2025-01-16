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
        Schema::create('acta_conclusion', function (Blueprint $table) {
            $table->bigIncrements('ac_id');
            $table->string('ac_titulo');
            $table->string('ac_descripcion');
            $table->string('ac_nota');
            $table->string('ac_documento');
            $table->foreignId('pi_id')
                ->constrained()
                ->onDelete('cascade')
                ->references('pi_id')
                ->on('programa_inscripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acta_conclusion');
    }
};
