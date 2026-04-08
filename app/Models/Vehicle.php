<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_no',
        'driver_name',
        'driver_phone',
        'capacity',
        'vehicle_type',
    ];

    public function busStops()
    {
        return $this->belongsToMany(BusStop::class, 'bus_stop_vehicle');
    }

    public function arrivals()
    {
        return $this->hasMany(StudentTransport::class, 'arrival_vehicle_id');
    }

    public function departures()
    {
        return $this->hasMany(StudentTransport::class, 'departure_vehicle_id');
    }
}
