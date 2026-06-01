<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->enum('estado', ['pendiente', 'confirmada', 'en_curso', 'finalizada', 'cancelada'])->default('pendiente');
            $table->string('direccion_recogida', 255)->nullable();
            $table->text('notas')->nullable();
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_perro')->constrained('perros')->onDelete('cascade');
            $table->foreignId('id_servicio')->constrained('servicios')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};