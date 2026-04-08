<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelRoom extends Model
{
    protected $fillable = ['hostel_id', 'room_no', 'type', 'capacity', 'description'];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function allotments()
    {
        return $this->hasMany(HostelAllotment::class, 'room_id');
    }
}
