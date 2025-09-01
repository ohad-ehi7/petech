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
        Schema::create('customers', function (Blueprint $table) {
    $table->id('CustomerID');
    $table->string('fullname');
    $table->string('nif_cin')->nullable(); // CIN/NIF mieux en string car ça peut contenir des lettres
    $table->string('phone')->nullable();   // Téléphone : string car peut contenir +509, tirets, etc.
    $table->string('address')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
