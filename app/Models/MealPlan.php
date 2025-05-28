<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    protected $table = 'meal_plans'; // Explicitly defining the table name (optional)

    protected $fillable = [
        'user_id',
        'meal_plan_name',
        'duration',
        'day_cal',
        'no_of_meals',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Define the relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
