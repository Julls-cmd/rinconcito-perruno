<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->decimal('descuento_porcentaje', 5, 2)->default(0);
            $table->decimal('descuento_fijo', 8, 2)->default(0);
            $table->string('condicion', 255)->nullable();
            $table->unsignedInteger('usos_maximos')->default(1);
            $table->unsignedInteger('usos_restantes')->default(1);
            $table->date('fecha_expiracion')->nullable();
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonos');
    }
};
