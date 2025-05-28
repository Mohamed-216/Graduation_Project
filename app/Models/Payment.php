<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments'; // Explicitly defining the table name (optional)

    protected $fillable = [
        'amount',
        'method',
        'user_id',
    ];

    /**
     * Define the relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
