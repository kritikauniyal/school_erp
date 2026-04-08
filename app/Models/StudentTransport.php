<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTransport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'bus_stop_id',
        'arrival_vehicle_id',
        'departure_vehicle_id',
        'monthly_charge',
        'start_date',
        'end_date',
        'status',
        'allotment_no',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function busStop()
    {
        return $this->belongsTo(BusStop::class);
    }

    public function arrivalVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'arrival_vehicle_id');
    }

    public function departureVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'departure_vehicle_id');
    }
}
