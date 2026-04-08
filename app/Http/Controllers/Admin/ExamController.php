<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    /**
     * Basic exams index page (to be expanded with schedules, subjects, and results).
     */
    public function index()
    {
        return view('exams.index');
    }
}

