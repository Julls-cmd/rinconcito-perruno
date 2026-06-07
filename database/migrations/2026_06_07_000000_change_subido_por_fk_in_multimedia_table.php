<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('multimedia', function (Blueprint $table) {
            $table->dropForeign(['subido_por']);
            $table->foreign('subido_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('multimedia', function (Blueprint $table) {
            $table->dropForeign(['subido_por']);
            $table->foreign('subido_por')->references('id')->on('empleados')->onDelete('set null');
        });
    }
};
