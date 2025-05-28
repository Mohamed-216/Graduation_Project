<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Explicitly defining the table name (optional)

    protected $fillable = [
        'cat_name',
    ];
}
