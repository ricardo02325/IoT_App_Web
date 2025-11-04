<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispositivos_particle', function (Blueprint $table) {
            $table->id(); // id auto_increment
            $table->string('device_id')->unique();
            $table->string('nombre')->nullable();
            
            // Debe ser bigInteger y UNSIGNED para coincidir con salones.id_salon
            $table->unsignedBigInteger('id_salon'); 

            // Timestamps con valor actual por defecto
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            // Clave foránea
            $table->foreign('id_salon')
                  ->references('id_salon')
                  ->on('salones')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositivos_particle');
    }
};