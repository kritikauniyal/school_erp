<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $table = 'fee_structures';

    protected $fillable = [
        'class_name',
        'fee_type_id',
        'amount',
        'monthly_amounts',
        'session',
        'effective_from',
        'is_active'
    ];

    protected $casts = [
        'monthly_amounts' => 'array',
        'is_active' => 'boolean'
    ];

    public function feeType()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }
}
