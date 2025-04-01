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
        Schema::create('montres__hommes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->binary('photo');
            $table->integer('prix');
            $table->string('genre')->default('homme')->nullable(false)
            ->comment('Ce champ est immuable et a pour valeur par défaut "homme".');
            $table->text('description');
            $table->integer('quantité');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('montres__hommes');
    }
};
