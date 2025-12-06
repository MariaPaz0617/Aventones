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
        Schema::create('activation_token', function (Blueprint $table) {
        $table->increments('id');

        $table->unsignedInteger('usuario_id');

        $table->string('token', 255);
        $table->dateTime('creado_en')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->tinyInteger('usado')->default(0);

        $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activation_token');
    }
};
