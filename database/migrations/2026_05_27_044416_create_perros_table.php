<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('raza', 100);
            $table->unsignedInteger('edad');
            $table->decimal('peso', 5, 2)->nullable();
            $table->enum('temperamento', ['tranquilo', 'activo', 'agresivo', 'sociable']);
            $table->boolean('vacunas')->default(false);
            $table->string('microchip', 20)->unique()->nullable();
            $table->text('observaciones')->nullable();
            $table->string('foto', 255)->nullable();
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perros');
    }
};
