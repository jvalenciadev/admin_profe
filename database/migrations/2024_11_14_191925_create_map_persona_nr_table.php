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
        Schema::create('map_persona_nr', function (Blueprint $table) {
            $table->bigIncrements('per_nr_id');
            $table->string('per_nac_provincia');
            $table->string('per_res_provincia');
            $table->string('per_nac_municipio');
            $table->string('per_res_municipio');
            $table->string('per_nac_localidad');
            $table->string('per_res_localidad');
            $table->string('per_res_dirrecion');
            $table->foreignId('per_id')
                ->constrained('map_persona')
                ->onDelete('cascade');
            $table->foreignId('dep_nac_id')
                ->constrained('departamento')
                ->onDelete('cascade');
            $table->foreignId('dep_res_id')
                ->constrained('departamento')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_persona_nr');
    }
};
