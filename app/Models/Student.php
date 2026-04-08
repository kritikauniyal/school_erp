<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
    ];

    // belongs to login user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function parent()
    {
        return $this->hasOne(StudentParent::class, 'student_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function classInfo()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function sectionInfo()
    {
        return $this->belongsTo(Section::class);
    }

  

    public function previousSchool()
    {
        return $this->hasOne(StudentPreviousSchool::class);
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : '';
    }

    public function getFatherNameAttribute()
    {
        return $this->parent_name;
    }

    public function hostelAllotments()
    {
        return $this->hasMany(HostelAllotment::class);
    }
}
