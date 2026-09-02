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
        Schema::create('numeros_emergencia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: "Bomberos", "Policía"
            $table->string('numero'); // Ej: "100", "911"
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numeros_emergencia');
    }
};
