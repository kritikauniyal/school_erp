<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeConcessionController extends Controller
{
    public function index(Request $request)
    {
        $sections = \App\Models\Section::all();
        $feeTypes = \App\Models\FeeType::all();
        
        $query = \App\Models\FeeConcession::with(['student.class', 'student.section', 'student.user', 'feeType']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('student', function($q) use ($keyword) {
                $q->where('admission_no', 'LIKE', "%{$keyword}%")
                  ->orWhere('student_name', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('user', function($qu) use ($keyword) {
                      $qu->where('name', 'LIKE', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('class')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class);
            });
        }

        if ($request->filled('section')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('section_id', $request->section);
            });
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('fee_name')) {
            $query->where('fee_type_id', $request->fee_name);
        }

        $concessions = $query->paginate(10);

        return view('pages.fee.fee-concession', compact('sections', 'feeTypes', 'concessions'));
    }
}
