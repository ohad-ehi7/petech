<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    protected $primaryKey = 'SupplierID';

    public function productSuppliers(): HasMany
    {
        return $this->hasMany(ProductSupplier::class, 'SupplierID', 'SupplierID');
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, ProductSupplier::class, 'SupplierID', 'ProductID', 'SupplierID', 'ProductID');
    }
}
