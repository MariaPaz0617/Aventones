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
    Schema::create('usuarios', function (Blueprint $table) {
    $table->increments('id');

    $table->unsignedInteger('rol_id');
    $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');

    $table->string('nombre', 100);
    $table->string('apellido', 100);
    $table->string('cedula', 50)->unique();
    $table->date('fecha_nacimiento');
    $table->string('email', 255)->unique();
    $table->string('telefono', 50)->nullable();
    $table->string('foto', 255)->nullable();
    $table->string('password', 255);
    $table->string('estado', 20)->default('PENDIENTE');

    $table->dateTime('creado_en')->default(DB::raw('CURRENT_TIMESTAMP'));
    $table->dateTime('actualizado_en')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

    $table->tinyInteger('activo')->default(1);
    $table->string('token', 255)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
