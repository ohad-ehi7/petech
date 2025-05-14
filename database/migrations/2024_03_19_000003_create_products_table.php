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
        Schema::create('products', function (Blueprint $table) {
            $table->id('ProductID');
            $table->string('ProductName');
            $table->string('Unit');
            $table->boolean('IsReturnable')->default(false);
            $table->foreignId('CategoryID')->constrained('categories', 'CategoryID')->onDelete('cascade');
            $table->foreignId('SupplierID')->nullable()->constrained('suppliers', 'SupplierID')->onDelete('set null');
            $table->string('Brand')->nullable();
            $table->string('SKU')->unique()->nullable();
            $table->text('Description')->nullable();
            $table->decimal('Weight', 10, 2)->nullable();
            $table->string('WeightUnit')->nullable();
            $table->decimal('SellingPrice', 10, 2);
            $table->decimal('CostPrice', 10, 2);
            $table->integer('OpeningStock')->nullable();
            $table->integer('ReorderLevel')->nullable();
            $table->string('Product_Image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
