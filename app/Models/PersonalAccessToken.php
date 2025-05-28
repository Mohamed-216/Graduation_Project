<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PersonalAccessToken extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'personal_access_tokens'; // Explicitly defining the table name

    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Define the polymorphic relationship.
     */
    public function tokenable()
    {
        return $this->morphTo();
    }
}
