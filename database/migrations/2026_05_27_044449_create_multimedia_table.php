<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('multimedia', function (Blueprint $table) {
            $table->id();
            $table->string('ruta_archivo', 500);
            $table->enum('tipo', ['foto', 'video']);
            $table->string('descripcion', 255)->nullable();
            $table->foreignId('id_reserva')->constrained('reservas')->onDelete('cascade');
            $table->foreignId('id_perro')->constrained('perros')->onDelete('cascade');
            $table->foreignId('subido_por')->nullable()->constrained('empleados')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multimedia');
    }
};
