<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tipos_sensores', function (Blueprint $table) {
            $table->id('id_tipo_sensor');
            $table->string('nombre_tipo', 50)->unique();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tipos_sensores');
    }
};