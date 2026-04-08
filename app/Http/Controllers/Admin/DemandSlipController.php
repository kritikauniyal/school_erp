<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DemandSlipController extends Controller
{
    public function index()
    {
        $globalClasses = \App\Models\SchoolClass::orderBy('id')->get();
        $sections = \App\Models\Section::orderBy('id')->get();
        
        return view('pages.fee.demand-slip', compact('globalClasses', 'sections'));
    }

    public function getStudents(Request $request)
    {
        
        $className = $request->input('class');
        $sectionName = $request->input('section');

        if (!$className || !$sectionName) {
            return response()->json([]);
        }

        // Fetch students actively admitted and enrolled in this class/section
        $students = \App\Models\Student::with('parent','classInfo','section')
                    ->whereHas('classInfo', function ($q) use ($className) {
                        $q->where('name', $className);
                    })
                    ->whereHas('section', function ($q) use ($sectionName, $className) {
                        $q->where('name', $sectionName)
                        ->whereHas('class', function ($q2) use ($className) {
                            $q2->where('name', $className);
                        });
                    })
                    ->where('is_active', 1)
                    ->get();
                        // dd($students);
        // Fetch active fee structure for this class
        $feeStructures = \Illuminate\Support\Facades\DB::table('fee_structures')
                            ->join('fee_types', 'fee_structures.fee_type_id', '=', 'fee_types.id')
                            ->where('fee_structures.class_name', $className)
                            ->where('fee_structures.is_active', true)
                            ->select('fee_types.name as fee_name', 'fee_structures.amount')
                            ->get();

        $monthlyFee = [];
        foreach ($feeStructures as $fs) {
            $monthlyFee[$fs->fee_name] = (float)$fs->amount;
        }

        $result = [];
        foreach ($students as $student) {
            // Get latest ledger balance (Back Dues/Arrears)
            $lastLedger = \App\Models\StudentLedger::where('student_id', $student->id)
                            ->orderByDesc('date')->orderByDesc('id')->first();
            $backDues = $lastLedger ? (float)$lastLedger->balance : 0;

            $result[] = [
                'sid' => $student->admission_no ?? 'SID'.$student->id, // fallback
                'name' => $student->student_name,
                'father' => $student->parent ? $student->parent->father_name : 'N/A',
                'roll' => $student->roll_no ?? 'N/A',
                'address' => $student->address_1 ?? 'N/A',
                'monthlyFee' => $monthlyFee,
                'backDues' => $backDues
            ];
        }

        return response()->json($result);
    }
}
