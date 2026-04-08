<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    /**
     * Basic attendance index page (to be expanded with full logic later).
     */
    public function index()
    {
        return view('attendance.index');
    }
}

