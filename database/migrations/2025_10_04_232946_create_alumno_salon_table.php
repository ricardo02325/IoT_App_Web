<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumno_salon', function (Blueprint $table) {
            $table->id('id_relacion');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_salon');

            $table->unique(['id_usuario', 'id_salon']);

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_salon')->references('id_salon')->on('salones')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('alumno_salon');
    }
};