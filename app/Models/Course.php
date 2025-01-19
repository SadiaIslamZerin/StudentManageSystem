<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Course extends Model
{
    use HasFactory;

    // Define the table if it's not following the naming convention
    protected $table = 'courses';  // This is optional if the table name follows the plural convention (courses)

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'duration',
        'start_date',
        'end_date',
        'class_start_hour',
        'class_end_hour',
        'classdays',
        'fees'
    ];
}
