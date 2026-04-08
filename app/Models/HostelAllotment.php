<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelAllotment extends Model
{
    protected $fillable = [
        'allotment_no',
        'student_id',
        'room_id',
        'allotment_date',
        'discharge_date',
        'monthly_charge',
        'status',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function room()
    {
        return $this->belongsTo(HostelRoom::class, 'room_id');
    }
}
