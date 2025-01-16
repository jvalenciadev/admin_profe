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
        Schema::create('barcode', function (Blueprint $table) {
            $table->bigIncrements('bar_id');
            $table->string('bar_md5');
            $table->string('bar_descripcion');
            $table->enum('bar_tipo', ['CERTIFICADO', 'REGISTRO ACADEMICO', 'PAGOS'])->default('PAGOS');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcode');
    }
};
