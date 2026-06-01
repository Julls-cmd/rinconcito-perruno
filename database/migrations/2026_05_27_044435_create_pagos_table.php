<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->decimal('importe', 8, 2);
            $table->enum('metodo', ['tarjeta', 'efectivo', 'transferencia']);
            $table->enum('estado', ['pendiente', 'completado', 'fallido', 'reembolsado'])->default('pendiente');
            $table->string('stripe_payment_id', 255)->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->foreignId('id_reserva')->unique()->constrained('reservas')->onDelete('cascade');
            $table->foreignId('id_bono')->nullable()->constrained('bonos')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};