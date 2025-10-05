<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sensores', function (Blueprint $table) {
            $table->id('id_sensor');
            $table->unsignedBigInteger('id_tipo_sensor');
            $table->unsignedBigInteger('id_salon');
            $table->string('descripcion', 100)->nullable();

            $table->foreign('id_tipo_sensor')->references('id_tipo_sensor')->on('tipos_sensores')->onDelete('cascade');
            $table->foreign('id_salon')->references('id_salon')->on('salones')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sensores');
    }
};