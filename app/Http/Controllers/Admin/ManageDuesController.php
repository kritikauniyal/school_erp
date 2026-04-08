<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentLedger;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Services\AccountingService;
use Illuminate\Support\Facades\DB;

class ManageDuesController extends Controller
{
    public function index()
    {
        $globalClasses = SchoolClass::orderBy('id')->get();
        $sections = Section::orderBy('id')->get();
        return view('pages.fee.manage-dues', compact('globalClasses', 'sections'));
    }

    public function getStudents(Request $request)
    {
        $classId = $request->input('class');
        $sectionId = $request->input('section');
        
        $query = Student::with(['parent', 'classInfo', 'sectionInfo'])
                    ->where('is_active', 1);

        if ($classId && $classId !== 'Select') {
            $query->where('class_id', $classId);
        }
        if ($sectionId && $sectionId !== 'Select') {
            $query->where('section_id', $sectionId);
        }

        $students = $query->get();

        $result = [];
        foreach ($students as $student) {
            // Get latest ledger balance (Back Dues/Arrears)
            $lastLedger = StudentLedger::where('student_id', $student->id)
                            ->orderByDesc('date')->orderByDesc('id')->first();
            $backDues = $lastLedger ? (float)$lastLedger->balance : 0;

            $result[] = [
                'id' => $student->id,
                'sid' => 'SID'.$student->id,
                'admNo' => $student->admission_no ?? '',
                'studentName' => $student->student_name,
                'class' => $student->classInfo ? $student->classInfo->name : 'N/A',
                'section' => $student->sectionInfo ? $student->sectionInfo->name : 'N/A',
                'roll' => $student->roll_no ?? '',
                'father' => $student->parent ? $student->parent->father_name : 'N/A',
                'mobile1' => $student->mobile ?? '',
                'mobile2' => $student->phone ?? '',
                'remarks' => $student->remarks ?? '',
                'dues' => $backDues
            ];
        }

        return response()->json($result);
    }

    public function updateStudents(Request $request)
    {
        $updates = $request->input('students', []);
        
        if (empty($updates)) {
            return response()->json(['status' => 'success']);
        }

        try {
            DB::beginTransaction();
            $accounting = app(AccountingService::class);

            foreach($updates as $data) {
                $student = Student::find($data['id']);
                if (!$student) continue;

                if (isset($data['admNo'])) {
                    // Check if ADM number is unique among other logic? Assuming uniqueness check might fail, we should handle it.
                    // But for now, we just update it.
                    $student->admission_no = $data['admNo'];
                }
                if (isset($data['roll'])) $student->roll_no = $data['roll'];
                
                $student->save();

                if (isset($data['dues'])) {
                    $newDues = (float) $data['dues'];
                    
                    $lastLedger = StudentLedger::where('student_id', $student->id)
                                    ->orderByDesc('date')->orderByDesc('id')->first();
                    $currentDues = $lastLedger ? (float)$lastLedger->balance : 0;

                    if ($currentDues !== $newDues) {
                        $diff = $newDues - $currentDues;
                        $type = $diff > 0 ? 'Fee/Arrears' : 'Credit/Adjustment';
                        
                        $accounting->addLedgerEntry(
                            studentId: $student->id,
                            transactionType: $type,
                            amount: abs($diff),
                            referenceType: 'ManualDuesUpdate',
                            referenceId: null,
                            description: 'Admin manually updated Dues to ₹' . $newDues
                        );
                    }
                }
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Students updated successfully']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
