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
        Schema::create('logs', function (Blueprint $table) {
        $table->increments('id');

        $table->unsignedInteger('usuario_id');

        $table->string('accion', 100);
        $table->text('detalle')->nullable();

        $table->dateTime('creado_en')->default(DB::raw('CURRENT_TIMESTAMP'));

        // FK
        $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
