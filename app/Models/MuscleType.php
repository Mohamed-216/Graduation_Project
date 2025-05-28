<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuscleType extends Model
{
    use HasFactory;

    protected $table = 'muscle_types'; // Explicitly defining the table name (optional)

    protected $fillable = [
        'exercise_id',
        'muscle_type',
    ];

    /**
     * Define the relationship with Exercise.
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
