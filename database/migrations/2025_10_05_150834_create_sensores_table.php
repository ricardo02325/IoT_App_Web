<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sensores', function (Blueprint $table) {
            $table->id('id_sensor');           // debe coincidir con la FK
            $table->string('tipo', 50);
            $table->unsignedBigInteger('id_salon'); // coincidir con salones.id_salon
            $table->foreign('id_salon')
                ->references('id_salon')
                ->on('salones')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensores');
    }
};