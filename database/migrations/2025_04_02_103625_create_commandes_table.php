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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client');
            $table->string('prenom_client');
            $table->string('quartier_client');
            $table->string('telephone_client',20);
            $table->string('photo_montre');
            $table->string('nom_montre');
            $table->string('genre_montre');
            $table->string('description_montre');
            $table->integer('prix_unitaire_montre');
            $table->integer('quantite_montre');
            $table->integer('prix_total_montre');
            $table->unsignedBigInteger('montre_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
