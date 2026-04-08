<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateFine extends Model
{
    protected $fillable = [
        'classes',
        'month',
        'from_date',
        'to_date',
        'amount',
        'is_active'
    ];

    protected $casts = [
        'classes' => 'array',
        'is_active' => 'boolean',
        'from_date' => 'date',
        'to_date' => 'date'
    ];
}
