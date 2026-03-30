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
        Schema::create('servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establecimiento_id')->constrained('establecimiento')->onDelete('cascade');
            $table->string('nombre_servicio',250);
            $table->text('descripcion_servicio');
            $table->decimal('precio',10, 2);
            $table->enum('tipo',['SERVICIO', 'PRODUCTO'])->default('SERVICIO');
            $table->string('icono', 70);
            $table->tinyInteger('disponible')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio');
    }
};
