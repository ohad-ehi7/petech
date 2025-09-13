<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('SupplierID');
            $table->string('SupplierName');
            $table->string('ContactNumber')->nullable();
            $table->string('Email')->nullable();
            $table->string('Address')->nullable();
            $table->timestamps();
        });

        // Ajouter un fournisseur "Local" par défaut
        DB::table('suppliers')->insert([
            'SupplierName' => 'Local',
            'ContactNumber' => '000000000',
            'Email' => 'local@gmail.com',
            'Address' => 'cap-haitien',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
