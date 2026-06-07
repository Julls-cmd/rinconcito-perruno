<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellidos', 150);
            $table->enum('rol', ['cuidador', 'administrativo', 'esteticista', 'conductor']);
            $table->enum('turno', ['mañana', 'tarde', 'noche']);
            $table->string('telefono', 15)->nullable();
            $table->string('email', 150)->unique()->nullable();
            $table->date('fecha_alta');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
