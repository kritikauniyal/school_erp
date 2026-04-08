<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'dob'
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    // user belongs to role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // user has one student profile
    public function student()
    {
        return $this->hasOne(Student::class);
    }
}
