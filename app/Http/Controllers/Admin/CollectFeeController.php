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
        
        if ($request->has('get_sections')) {
            return $this->getSections($request->class_id);
        }
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
            $classId = $request->class;
            $classObj = \App\Models\SchoolClass::find($classId);
            $query->where(function($q) use ($classId, $classObj) {
                $q->where('class_id', $classId);
                if ($classObj) {
                    $q->orWhere('class_id', $classObj->name);
                }
            });
        }

        if ($request->filled('section')) {
            $sectionId = $request->section;
            $sectionObj = \App\Models\Section::find($sectionId);
            $query->where(function($q) use ($sectionId, $sectionObj) {
                $q->where('section_id', $sectionId);
                if ($sectionObj) {
                    $q->orWhere('section_id', $sectionObj->name);
                }
            });
        }

        if ($request->ajax() || $request->wantsJson()) {
            $students = $query->get()->map(function($student) {
                // Calculation of total dues
                $monthlyTotal = $student->studentFees ? $student->studentFees->sum('total') : 0;
                $totalBilled = $monthlyTotal * 12;
                $totalPaid = \App\Models\Payment::where('student_id', $student->id)
                    ->where('status', 'success')
                    ->where('is_cancelled', false)
                    ->sum('amount');
                $dues = max(0, $totalBilled - $totalPaid);

                return [
                    'id' => $student->id,
                    'sid' => 'SID' . str_pad($student->id, 5, '0', STR_PAD_LEFT),
                    'adm' => $student->admission_no,
                    'ledger' => $student->ledger_no ?? 'L' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
                    'roll' => $student->roll_no ?? 'N/A',
                    'name' => strtoupper($student->student_name),
                    'cls' => $student->classInfo->name ?? 'N/A',
                    'sec' => $student->section->name ?? 'N/A',
                    'father' => strtoupper($student->parent->father_name ?? 'N/A'),
                    'mob' => $student->mobile ?? 'N/A',
                    'dues' => (float)$dues,
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

    public function getSections($classId)
    {
        $sections = Section::where('class_id', $classId)->get();
        return response()->json($sections);
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

    public function getReceipt(Request $request, $paymentId)
    {
        if ($paymentId === 'latest') {
            $payment = \App\Models\Payment::where('student_id', $request->student_id)
                ->where('status', 'success')
                ->where('is_cancelled', false)
                ->latest()
                ->first();
        } else {
            $payment = \App\Models\Payment::find($paymentId);
        }

        if (!$payment) return response()->json(['success' => false, 'message' => 'Payment not found']);

        $student = Student::with(['classInfo', 'section', 'parent'])->find($payment->student_id);
        
        return response()->json([
            'success' => true,
            'payment' => $payment,
            'student' => $student
        ]);
    }

    public function pay(Request $request, $id)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'payment_mode' => 'required|string'
            ]);

            $student = Student::findOrFail($id);

            $payment = new \App\Models\Payment();
            $payment->student_id = $student->id;
            $payment->amount = $request->amount;
            $payment->gateway = $request->payment_mode;
            $payment->transaction_id = $request->reference_no;
            $payment->status = 'success';
            $payment->is_cancelled = false;
            $payment->save();

            try {
                $accounting = app(\App\Services\AccountingService::class);
                $accounting->addLedgerEntry($student->id, now(), 'Payment', $request->amount, \App\Models\Payment::class, $payment->id, "Fee Payment via " . $request->payment_mode . " " . ($request->remark ? "({$request->remark})" : ""));
            } catch (\Exception $e) {
                // Safely silently ignore if accounting service missing
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'payment' => $payment,
                'student' => $student->load(['classInfo', 'section', 'parent'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
