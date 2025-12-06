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
        Schema::create('vehiculos', function (Blueprint $table) {
        $table->increments('id');

        $table->unsignedInteger('usuario_id');

        $table->string('placa', 20);
        $table->string('color', 50);
        $table->string('marca', 100);
        $table->string('modelo', 100);
        $table->year('anio');
        $table->integer('capacidad_asientos');
        $table->string('foto', 255)->nullable();

        $table->dateTime('creado_en')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->dateTime('actualizado_en')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

        // FK
        $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
