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
        Schema::create('rides', function (Blueprint $table) {
        $table->increments('id');

        $table->unsignedInteger('usuario_id');
        $table->unsignedInteger('vehiculo_id');

        $table->string('nombre', 150);
        $table->string('lugar_salida', 255);
        $table->string('lugar_llegada', 255);
        $table->date('fecha');
        $table->time('hora');
        $table->decimal('costo', 10, 2);
        $table->integer('cantidad_espacios');

        $table->dateTime('creado_en')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->dateTime('actualizado_en')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        $table->tinyInteger('activo')->default(1);

        // FKs
        $table->foreign('usuario_id')->references('id')->on('usuarios');
        $table->foreign('vehiculo_id')->references('id')->on('vehiculos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
