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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('TransactionID');
            $table->foreignId('ProductID')->constrained('products', 'ProductID')->onDelete('cascade');
            $table->string('TransactionType');
            $table->timestamp('TransactionDate')->useCurrent();
            $table->integer('QuantityChange');
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('TotalAmount', 10, 2);
            $table->foreignId('ReferenceID')->nullable()->constrained('sales', 'SaleID'); // Assuming primary reference is to sales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
