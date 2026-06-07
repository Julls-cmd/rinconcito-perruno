<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas_empleados', function (Blueprint $table) {
            $table->foreignId('id_reserva')->constrained('reservas')->onDelete('cascade');
            $table->foreignId('id_empleado')->constrained('empleados')->onDelete('cascade');
            $table->enum('rol_en_reserva', ['cuidador', 'conductor', 'esteticista']);
            $table->primary(['id_reserva', 'id_empleado']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas_empleados');
    }
};
