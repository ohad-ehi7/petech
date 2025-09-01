<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'RoleID',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relation avec le rôle
    public function role()
    {
        return $this->belongsTo(Role::class, 'RoleID', 'RoleID');
    }

    // Vérifier si l'utilisateur a un rôle spécifique
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }
}
