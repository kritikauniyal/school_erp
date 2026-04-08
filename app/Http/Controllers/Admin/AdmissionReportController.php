<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission;
use Carbon\Carbon;

class AdmissionReportController extends Controller
{
    public function index(Request $request)
    {
        $session = $request->input('session', '2025-2026');
        $fromDate = $request->input('from_date', date('Y-m-01', strtotime('-1 month'))); // Default to last 2 months
        $toDate = $request->input('to_date', date('Y-m-t'));
        $className = $request->input('class_name');

        // Query Students instead of Admissions for a full report of admitted students
        $query = \App\Models\Student::with(['classInfo', 'studentFees']);

        // Apply filters
        $query->where('session', $session)
              ->whereBetween('admission_date', [$fromDate, $toDate]);

        if ($className) {
            $query->whereHas('classInfo', function ($q) use ($className) {
                $q->where('name', 'LIKE', "%{$className}%");
            });
        }

        $students = $query->get();

        // Group and summarize by class
        $summaryData = [];
        $admissions = [];

        foreach ($students as $student) {
            $currClass = $student->classInfo ? $student->classInfo->name : 'Unknown';
            
            // Calculate total admission fee for this student
            $studentTotalFee = $student->studentFees->sum('amount');

            if (!isset($summaryData[$currClass])) {
                $summaryData[$currClass] = [
                    'students' => 0,
                    'totalFee' => 0,
                ];
            }
            $summaryData[$currClass]['students'] += 1;
            $summaryData[$currClass]['totalFee'] += $studentTotalFee;

            // Map student for consistent variable in view
            $admissions[] = (object)[
                'date' => $student->admission_date,
                'student_name' => $student->student_name,
                'class_name' => $currClass,
                'fee_collected' => $studentTotalFee,
                'session' => $student->session,
                'status' => 'Admitted',
                'admission_no' => $student->admission_no,
            ];
        }

        ksort($summaryData);

        return view('pages.enquiry.admission-report', compact('summaryData', 'admissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'student_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:50',
            'fee_collected' => 'required|numeric|min:0',
            'session' => 'required|string|max:50',
            'status' => 'required|string|max:50'
        ]);

        Admission::create($request->all());

        return redirect()->back()->with('success', 'Admission record created successfully.');
    }

    public function show($id)
    {
        $admission = Admission::findOrFail($id);
        return response()->json(['success' => true, 'data' => $admission]);
    }

    public function edit($id)
    {
        $admission = Admission::findOrFail($id);
        return response()->json(['success' => true, 'data' => $admission]);
    }

    public function update(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);

        $request->validate([
            'date' => 'required|date',
            'student_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:50',
            'fee_collected' => 'required|numeric|min:0',
            'session' => 'required|string|max:50',
            'status' => 'required|string|max:50'
        ]);

        $admission->update($request->all());

        return redirect()->back()->with('success', 'Admission record updated successfully.');
    }

    public function destroy($id)
    {
        Admission::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Admission record deleted successfully.');
    }
}
