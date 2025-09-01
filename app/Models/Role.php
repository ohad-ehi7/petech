<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'RoleID'; // clé primaire personnalisée
    public $incrementing = true;

    protected $fillable = [
        'name'
    ];

    // Un rôle a plusieurs utilisateurs
    public function users()
    {
        return $this->hasMany(User::class, 'RoleID', 'RoleID');
        // foreign key dans users = RoleID, local key dans roles = RoleID
    }
}
