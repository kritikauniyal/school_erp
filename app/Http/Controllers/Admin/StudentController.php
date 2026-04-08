<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;

class StudentController extends Controller
{
    public function __construct(
        protected StudentRepository $students
    ) {
    }

    /**
     * Display a listing of the students.
     */
    public function index()
    {
        $students = $this->students->withRelations()->paginate(20);

        return view('students.index', compact('students'));
    }

    public function studentDetails()
    {
        $student = \App\Models\Student::with('parent', 'previousSchool', 'classInfo', 'sectionInfo')->first() ?? new \App\Models\Student();

        return view('pages.student.details', compact('student'));
    }
}
