<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preinscripciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_perro', 100);
            $table->string('raza', 100);
            $table->unsignedInteger('edad');
            $table->decimal('peso', 5, 2)->nullable();
            $table->boolean('vacunas')->default(false);
            $table->enum('temperamento', ['tranquilo', 'activo', 'agresivo', 'sociable']);
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->foreignId('id_usuario')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nombre_contacto', 150)->nullable();
            $table->string('email_contacto', 150)->nullable();
            $table->string('telefono_contacto', 15)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preinscripciones');
    }
};