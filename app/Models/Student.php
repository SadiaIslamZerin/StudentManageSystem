<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'university',
        'email',
        'phone',
        'gender',
        'created_at',
        'updated_at'
    ];

    public $incrementing = false;

    protected $keyType = 'string';
}
