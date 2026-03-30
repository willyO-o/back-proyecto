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
        Schema::create('establecimiento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',255);
            $table->text('descripcion')->nullable();
            $table->string('direccion', 250);
            $table->string('imagen');
            $table->string('telefono',25)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 150)->nullable();
            $table->string('horario_apertura', 8);
            $table->string('horario_cierre', 8);
            $table->string('latitud');
            $table->string('longitud');
            $table->enum('estado', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
            $table->foreignId('categoria_id')->constrained('categoria')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establecimiento');
    }
};
