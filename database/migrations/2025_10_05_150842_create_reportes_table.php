<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id('id_reporte');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_salon')->nullable(); // coincidir con salones.id_salon
            $table->enum('estatus', ['pendiente', 'en_proceso', 'completado'])->default('pendiente'); // NUEVO CAMPO
            $table->foreign('id_salon')
                ->references('id_salon')
                ->on('salones')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};