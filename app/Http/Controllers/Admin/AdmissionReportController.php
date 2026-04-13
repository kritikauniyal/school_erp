<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Carbon\Carbon;

class AdmissionReportController extends Controller
{
    public function index(Request $request)
    {
        $session = $request->input('session', '2025-2026');
        $fromDate = $request->input('from_date'); 
        $toDate = $request->input('to_date');
        $className = $request->input('class_name');

        $classes = \App\Models\SchoolClass::orderBy('id')->get();

        // Query Student model to ensure sync with Student Admission Manager
        $query = Student::with(['classInfo', 'studentFees']);

        // Apply Session Filter
        if ($session) {
            $query->where('session', $session);
        }

        // Apply Date Range Filter if provided
        if ($fromDate && $toDate) {
            $query->whereBetween('admission_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('admission_date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('admission_date', '<=', $toDate);
        }

        if ($className) {
            $query->whereHas('classInfo', function($q) use ($className) {
                $q->where('name', 'LIKE', "%{$className}%");
            });
        }

        $studentRecords = $query->get();

        $summaryData = [];
        $admissions = [];

        foreach ($studentRecords as $student) {
            $currClass = $student->classInfo->name ?? 'N/A';
            $feeCollected = $student->studentFees->sum('amount');

            if (!isset($summaryData[$currClass])) {
                $summaryData[$currClass] = [
                    'students' => 0,
                    'totalFee' => 0,
                ];
            }
            $summaryData[$currClass]['students'] += 1;
            $summaryData[$currClass]['totalFee'] += $feeCollected;

            $admissions[] = (object)[
                'date' => $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('Y-m-d') : null,
                'student_name' => $student->student_name,
                'class_name' => $currClass,
                'fee_collected' => $feeCollected,
                'session' => $student->session,
                'status' => $student->is_active ? 'Active' : 'Inactive',
                'admission_no' => $student->admission_no,
            ];
        }

        ksort($summaryData);

        return view('pages.enquiry.admission-report', compact('summaryData', 'admissions', 'classes'));
    }
}
