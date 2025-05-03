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
            $table->foreignId('CategoryFK')->constrained('categories', 'CategoryID')->onDelete('cascade');
            $table->foreignId('SupplierFK')->nullable()->constrained('suppliers', 'SupplierID')->onDelete('set null');
            $table->string('SKU')->unique()->nullable();
            $table->text('Description')->nullable();
            $table->string('Dimension')->nullable();
            $table->decimal('Weight', 10, 2)->nullable();
            $table->decimal('SellingPrice', 10, 2);
            $table->decimal('SalesTaxRate', 5, 2)->nullable();
            $table->decimal('CostPrice', 10, 2);
            $table->decimal('PurchaseTaxRate', 5, 2)->nullable();
            $table->integer('ReorderLevel')->nullable();
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
