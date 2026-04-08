<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function registrationStudent()
    {
        return $this->belongsTo(RegistrationStudent::class);
    }
}
