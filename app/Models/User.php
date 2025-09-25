<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'weight',
        'height',
        'age',
        'goal',
        'ingredients',
        'dietary_preferences',
        'allergies',
        'kitchen_equipment',
        'recipe_tokens',
        'question_tokens',
        'language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'ingredients' => 'array',
        'dietary_preferences' => 'array',
        'allergies' => 'array',
        'kitchen_equipment' => 'array',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    // Relationships
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
