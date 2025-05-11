<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $primaryKey = 'ProductID';

    protected $fillable = [
        'ProductName',
        'Unit',
        'IsReturnable',
        'CategoryID',
        'SupplierID',
        'Brand',
        'SKU',
        'Description',
        'Weight',
        'WeightUnit',
        'SellingPrice',
        'CostPrice',
        'OpeningStock',
        'ReorderLevel',
        'Product_Image'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Get the last product
            $lastProduct = self::orderBy('ProductID', 'desc')->first();
            $nextNumber = $lastProduct ? $lastProduct->ProductID + 1 : 1;
            
            // Generate SKU in format PROD-{00x}
            $product->SKU = 'PROD-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    // Many to many from Supplier table
    public function productSuppliers(): HasMany
    {
        return $this->hasMany(ProductSupplier::class, 'ProductID', 'ProductID');
    }

    public function suppliers(): HasManyThrough
    {
        return $this->hasManythrough(Supplier::class, ProductSupplier::class, 'ProductID', 'SupplierID', 'ProductID', 'SupplierID');
    }

    // Many to many from Sale table
    public function salesItems(): HasMany
    {
        return $this->hasMany(SalesItem::class, 'ProductID', 'ProductID');
    }

    public function sales(): HasManyThrough
    {
        return $this->hasManyThrough(Sale::class, SalesItem::class, 'ProductID', 'SaleID', 'ProductID', 'SaleID');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'ProductID', 'ProductID');
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class, 'ProductID', 'ProductID');
    }
}
