<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_admission_fee',
        'default_amount',
        'applicable_months'
    ];

    protected $casts = [
        'applicable_months' => 'array',
        'is_admission_fee' => 'boolean'
    ];
}
