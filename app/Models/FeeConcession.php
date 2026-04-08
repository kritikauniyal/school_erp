<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeConcession extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}
