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
        Schema::create('reservas', function (Blueprint $table) {
        $table->increments('id');

        $table->unsignedInteger('ride_id');
        $table->unsignedInteger('pasajero_id');

        $table->dateTime('fecha_solicitud')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->integer('cantidad_asientos');

        $table->enum('estado', ['PENDIENTE', 'ACEPTADA', 'RECHAZADA', 'CANCELADO'])
              ->default('PENDIENTE');

        $table->string('comentario', 500)->nullable();

        $table->dateTime('actualizado_en')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        $table->tinyInteger('notificado')->default(0);

        // FKs
        $table->foreign('ride_id')->references('id')->on('rides');
        $table->foreign('pasajero_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
