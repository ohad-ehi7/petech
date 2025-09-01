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
        Schema::create('roles', function (Blueprint $table) {
            $table->id('RoleID');
            $table->string('name');
            $table->timestamps();
        });

        // Ajouter des valeurs par défaut à la table roles
DB::table('roles')->insert([
    ['RoleID' => 1, 'name' => 'superadmin', 'created_at' => now(), 'updated_at' => now()],
    ['RoleID' => 2, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
    ['RoleID' => 3, 'name' => 'casher', 'created_at' => now(), 'updated_at' => now()],
]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
