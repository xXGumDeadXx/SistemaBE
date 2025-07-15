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
        Schema::create('persona', function (Blueprint $table) {
            $table->id('id_persona');
            $table->string('nombre_persona');
            $table->string('segundo_nombre_persona')->nullable();
            $table->string('apellido_persona');
            $table->string('segundo_apellido_persona')->nullable();
            $table->string('cedula_persona');
            $table->string('telefono_persona');
            $table->string('genero_persona');
            $table->integer('edad_persona');
            $table->date('fecha_nacimiento_persona');
            $table->string('email_persona');
            $table->boolean('regis_patria');
            $table->foreignId('id_perfil')->references('id_perfil')->on('perfil')->onDelete('cascade');
            $table->foreignId('id_sede')->references('id_sede')->on('sede')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
        
    }
};