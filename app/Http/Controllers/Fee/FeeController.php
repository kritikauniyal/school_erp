<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;

class FeeController extends Controller
{

    public function concession()
    {
        $classes = \App\Models\SchoolClass::all();
        $sections = \App\Models\Section::all();
        $feeTypes = \App\Models\FeeType::all();
        $concessions = \App\Models\FeeConcession::with(['student.classInfo', 'student.section', 'student.user', 'feeType'])->paginate(10);

        return view('pages.fee.fee-concession', compact('classes', 'sections', 'feeTypes', 'concessions'));
    }

    public function editConcession($id)
    {
        // To be implemented
        return redirect()->back();
    }

    public function deleteConcession($id)
    {
        // To be implemented
        return redirect()->back();
    }

    public function report()
    {
        $classes = SchoolClass::all();
        return view('pages.fee.fee-report', compact('classes'));
    }

    public function structure()
    {
        return view('pages.fee.fee-structure-manager');
    }

    

    public function collectFee()
    {
        $globalClasses = SchoolClass::all();
        $sections = Section::all();
        $students = Student::with(['classInfo', 'section', 'parent'])->paginate(10);

        return view('pages.fee.collect-fee', compact('globalClasses','sections','students'));
    }

   public function demandSlip()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();

        return view('pages.fee.demand-slip', compact('classes','sections'));
    }

    public function feeCollection(Request $request, $id = null)
    {
        $student = null;
        if ($id) {
            $student = \App\Models\Student::with(['classInfo', 'section', 'parent'])->find($id);
        } elseif ($request->filled('search') || $request->filled('class_id')) {
            $query = \App\Models\Student::with(['classInfo', 'section', 'parent']);
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            if ($request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('admission_no', $search)
                      ->orWhere('student_name', 'like', "%{$search}%");
                });
            }
            
            $student = $query->first();

            if ($student) {
                return redirect()->route('fee.collection', $student->id);
            } else {
                return redirect()->route('fee.collection')->with('error', 'No student found matching your criteria.');
            }
        }

        $months = [];
        if ($student) {
            $monthNames = ["April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March"];
            
            // Calculate standard monthly amount from student_fees
            $studentFees = \App\Models\StudentFee::where('student_id', $student->id)->get();
            $monthlyFee = $studentFees->sum('amount');
            $monthlyConcession = $studentFees->sum('concession');
            $monthlyTotal = $studentFees->sum('total');

            // Calculate total paid vs total billed dynamically for months
            // For simplicity, we assume fees are billed strictly sequentially
            $totalPaid = \App\Models\Payment::where('student_id', $student->id)->where('status', 'success')->where('is_cancelled', false)->sum('amount');
            
            // Wait, "paid" inside student_fees might contain the admission paid amount
            $admissionPaid = $studentFees->sum('paid');
            // We use total recognized payments
            $recognizedPaid = max($totalPaid, $admissionPaid);
            
            $remainingPaid = $recognizedPaid;

            foreach ($monthNames as $mKey => $mName) {
                $monthObj = new \stdClass();
                $monthObj->name = $mName;
                $monthObj->fee = $monthlyFee;
                $monthObj->concession = $monthlyConcession;
                $monthObj->total = $monthlyTotal;
                
                if ($remainingPaid >= $monthlyTotal) {
                    $monthObj->paid = $monthlyTotal;
                    $monthObj->dues = 0;
                    $remainingPaid -= $monthlyTotal;
                } elseif ($remainingPaid > 0) {
                    $monthObj->paid = $remainingPaid;
                    $monthObj->dues = $monthlyTotal - $remainingPaid;
                    $remainingPaid = 0;
                } else {
                    $monthObj->paid = 0;
                    $monthObj->dues = $monthlyTotal;
                }
                
                $months[] = $monthObj;
            }
        }
        
        $globalClasses = \App\Models\SchoolClass::all();

        return view('pages.fee.fee-collection', compact('student', 'months', 'globalClasses'));
    }


    public function quickCollect($id=null)
    {
        $classes = SchoolClass::all();
        $sections = Section::all();

        $student = null;

        if($id){
            $student = Student::find($id);
        }

        return view('pages.fee.quick-collect',
            compact('classes','sections','student')
        );
    }

    public function processPayment(Request $request, $id)
    {
        
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'mode' => 'required|string',
        
        ]);

        $student = Student::findOrFail($id);
        $amountToPay = (float) $request->amount;
        $discount = (float) $request->discount;
        

        // 1. Create a Payment Record (Receipt)
        $payment = \App\Models\Payment::create([
            'student_id' => $student->id,
            'amount' => $amountToPay,
            'payment_mode' => $request->mode,
            'status' => 'success',
            'date' => now(),
            // Add any other necessary fields
        ]);

        // 2. Log natively into Student Ledger via AccountingService
        $accountingService = new \App\Services\AccountingService();

        // Credit the actual paid amount
        $accountingService->addLedgerEntry(
            $student->id,
            now(),
            'Payment',
            $amountToPay,
            get_class($payment),
            $payment->id,
            "Fee Collection via " . ucfirst($request->mode)
        );

        // Credit the discount if any
        if ($discount > 0) {
            $accountingService->addLedgerEntry(
                $student->id,
                now(),
                'Discount',
                $discount,
                get_class($payment),
                $payment->id,
                "Discount applied during fee collection"
            );
        }

        // Return back with success
        return redirect()->back()->with('success', 'Payment of ₹' . number_format($amountToPay, 2) . ' processed successfully!');
    }
}