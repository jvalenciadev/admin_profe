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
        Schema::create('departamento', function (Blueprint $table) {
            $table->bigIncrements('dep_id');
            $table->string('dep_nombre');
            $table->string('dep_abreviacion');
            $table->enum('dep_estado', ['activo', 'inactivo','eliminado'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamento');
    }
};
