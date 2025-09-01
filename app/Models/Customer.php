<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    // use HasFactory;

    // protected $primaryKey = 'CustomerID';
    // protected $fillable = [
    //     'CustomerCode'
    // ];
    use HasFactory;

    protected $primaryKey = 'CustomerID';

    protected $fillable = [
        'fullname',
        'nif_cin',
        'phone',
        'address'
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'CustomerID', 'CustomerID');
    }
}
