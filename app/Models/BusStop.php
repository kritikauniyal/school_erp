<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_charge',
    ];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'bus_stop_vehicle');
    }

    public function transports()
    {
        return $this->hasMany(StudentTransport::class);
    }
}
