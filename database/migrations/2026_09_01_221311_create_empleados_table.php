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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            // Relación con la tabla users (el usuario que puede iniciar sesión)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Relación con la zona donde trabaja
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            
            // Datos personales adicionales
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->date('fecha_contratacion')->nullable();
            $table->string('tarea'); // Ej: "Mantenimiento", "Supervisor"
            
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
