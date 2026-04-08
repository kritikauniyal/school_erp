<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Section;
use App\Models\SchoolClass;

class CollectFeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['classInfo', 'section', 'parent', 'studentFees']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('student_name', 'like', "%{$keyword}%")
                  ->orWhere('admission_no', 'like', "%{$keyword}%")
                  ->orWhere('mobile', 'like', "%{$keyword}%")
                  ->orWhere('id', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('class')) {
            $query->where('class_id', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section_id', $request->section);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $students = $query->get()->map(function($student) {
                // Calculation of total dues (naively for now, matching the controller's existing logic if any)
                $monthlyTotal = $student->studentFees->sum('total');
                $totalBilled = $monthlyTotal * 12; // Adjusted to a full session
                $totalPaid = \App\Models\Payment::where('student_id', $student->id)->where('status', 'success')->where('is_cancelled', false)->sum('amount');
                $dues = max(0, $totalBilled - $totalPaid);

                return [
                    'id' => $student->id,
                    'sid' => 'SID' . $student->id,
                    'adm' => $student->admission_no,
                    'ledger' => $student->ledger_no ?? 'L' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
                    'roll' => $student->roll_no ?? 'N/A',
                    'name' => strtoupper($student->student_name),
                    'cls' => $student->classInfo->name ?? 'N/A',
                    'sec' => $student->section->name ?? 'N/A',
                    'father' => strtoupper($student->parent->father_name ?? 'N/A'),
                    'mob' => $student->mobile ?? 'N/A',
                    'dues' => $dues,
                ];
            });
            return response()->json(['success' => true, 'data' => $students]);
        }

        $students = $query->paginate(20);
        $students->getCollection()->transform(function($student) {
            $monthlyTotal = $student->studentFees->sum('total');
            $totalBilled = $monthlyTotal * 12;
            $totalPaid = \App\Models\Payment::where('student_id', $student->id)->where('status', 'success')->where('is_cancelled', false)->sum('amount');
            $dues = max(0, $totalBilled - $totalPaid);

            return (object) [
                'id' => $student->id,
                'sid' => 'SID' . $student->id,
                'adm' => $student->admission_no,
                'ledger' => $student->ledger_no ?? 'L' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
                'roll' => $student->roll_no ?? 'N/A',
                'name' => strtoupper($student->student_name),
                'cls' => $student->classInfo->name ?? 'N/A',
                'sec' => $student->section->name ?? 'N/A',
                'father' => strtoupper($student->parent->father_name ?? 'N/A'),
                'mob' => $student->mobile ?? 'N/A',
                'dues' => $dues,
            ];
        });
        $sections = Section::all();
        $globalClasses = SchoolClass::all();

        return view('pages.fee.collect-fee', compact('students', 'sections', 'globalClasses'));
    }
    public function getStudentFeeDetails($id)
    {
        $student = Student::with(['classInfo', 'section', 'parent', 'studentFees'])->find($id);
        if (!$student) return response()->json(['success' => false, 'message' => 'Student not found']);

        $monthNames = ["April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March"];
        
        $studentFees = $student->studentFees; // e.g. [{id, fee_name, amount, concession, total, paid}, ...]
        $monthlyTotal = $studentFees->sum('total');
        
        $payments = \App\Models\Payment::where('student_id', $student->id)
            ->where('status', 'success')
            ->where('is_cancelled', false)
            ->get();
        
        $totalPaid = $payments->sum('amount');
        $remainingPaid = $totalPaid;

        $months = [];
        foreach ($monthNames as $mKey => $mName) {
            $monthTotal = $monthlyTotal;
            $monthPaid = 0;
            $monthStatus = 'due';

            if ($remainingPaid >= $monthTotal) {
                $monthPaid = $monthTotal;
                $monthStatus = 'paid';
                $remainingPaid -= $monthTotal;
            } elseif ($remainingPaid > 0) {
                $monthPaid = $remainingPaid;
                $monthStatus = 'partial';
                $remainingPaid = 0;
            }

            // Breakdown by fee types (matching student_fees)
            $comps = [];
            foreach($studentFees as $sf) {
                // ... same logic for distribution ...
                $comps[] = [
                    'key' => strtolower(str_replace(' ', '_', $sf->fee_name)),
                    'name' => $sf->fee_name,
                    'total' => $sf->total,
                    'paid' => ($monthStatus === 'paid' ? $sf->total : ($monthStatus === 'partial' ? 0 : 0)), // Simple distribution logic
                    'balance' => ($monthStatus === 'paid' ? 0 : $sf->total),
                    'status' => ($monthStatus === 'paid' ? 'paid' : 'due')
                ];
            }

            $months[] = [
                'month' => $mName,
                'monthS' => substr($mName, 0, 3),
                'fee' => $monthTotal,
                'fine' => 0, // Late fine logic can be added later
                'concession' => $studentFees->sum('concession'),
                'net' => $monthTotal,
                'paid' => $monthPaid,
                'balance' => max(0, $monthTotal - $monthPaid),
                'status' => $monthStatus,
                'paidDate' => $monthPaid > 0 ? now()->format('d M') : '—', // This should be from payment records
                'comps' => $comps,
                'selected' => false,
                'receiptNo' => $monthPaid > 0 ? 'RC-' . rand(10000, 99999) : null
            ];
        }

        return response()->json([
            'success' => true,
            'student' => [
                'id' => $student->id,
                'name' => strtoupper($student->student_name),
                'adm' => $student->admission_no,
                'ledger' => $student->ledger_no ?? 'L' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
                'roll' => $student->roll_no ?? 'N/A',
                'cls' => $student->classInfo->name ?? 'N/A',
                'sec' => $student->section->name ?? 'N/A',
                'father' => strtoupper($student->parent->father_name ?? 'N/A'),
                'mob' => $student->mobile ?? 'N/A',
            ],
            'months' => $months
        ]);
    }
}
