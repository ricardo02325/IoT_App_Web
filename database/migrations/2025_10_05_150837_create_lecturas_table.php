<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lecturas', function (Blueprint $table) {
            $table->id('id_lectura');
            $table->unsignedBigInteger('id_sensor'); // coincidir con sensores.id_sensor
            $table->decimal('valor', 6, 2);
            $table->timestamp('fecha_hora')->useCurrent();

            $table->foreign('id_sensor')
                ->references('id_sensor')
                ->on('sensores')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturas');
    }
};