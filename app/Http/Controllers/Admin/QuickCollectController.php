<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuickCollectController extends Controller
{
    public function index(Request $request)
    {
        $student = null;
        $sections = \App\Models\Section::all();
        $globalClasses = \App\Models\SchoolClass::all();

        $months = [];

        if ($request->filled('search') || $request->filled('class_id') || $request->filled('section_id')) {
            $query = \App\Models\Student::with(['classInfo', 'section', 'parent', 'user']);
            
            // Apply search keywords only if provided
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'LIKE', "%{$search}%")
                      ->orWhere('admission_no', 'LIKE', "%{$search}%")
                      ->orWhere('mobile', 'LIKE', "%{$search}%");
                });
            }

            // Apply Class/Section filters if present
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            if ($request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }

            $student = $query->first();

            if ($student) {
                // Fetch applicable late fines for this student's class
                $studentClassName = $student->classInfo ? $student->classInfo->name : '';
                
                $lateFines = \App\Models\LateFine::where('is_active', true)
                    ->whereJsonContains('classes', $studentClassName)
                    ->get();

                // Calculate fee data
                $feeStructure = \App\Models\FeeStructure::where('class_name', $studentClassName)
                    ->where('session', '2025-2026') 
                    ->where('is_active', true)
                    ->get();
                
                $monthlyFee = $feeStructure->sum('amount');
                $monthlyConcession = 0; // TODO implement concessions if needed
                $monthlyTotal = $monthlyFee - $monthlyConcession;

                $monthNames = ["APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER", "JANUARY", "FEBRUARY", "MARCH"];

                $accountingService = app(\App\Services\AccountingService::class);
                $ledgerBalance = $accountingService->rebuildStudentLedger($student->id);
                
                // Total paid is the sum of all payments/discounts/reversals
                $totalPaid = \Illuminate\Support\Facades\DB::table('student_ledgers')
                    ->where('student_id', $student->id)
                    ->whereIn('transaction_type', ['Payment', 'Reversal', 'Discount'])
                    ->sum('amount');

                $remainingPaid = $totalPaid;
                $today = now()->startOfDay();

                foreach ($monthNames as $mKey => $mName) {
                    $monthObj = new \stdClass();
                    $monthObj->name = $mName;
                    $monthObj->fee = $monthlyFee;
                    $monthObj->concession = $monthlyConcession;
                    $monthObj->late_fine = 0;
                    
                    // Check for late fine
                    $applicableFine = $lateFines->where('month', ucwords(strtolower($mName)))->first();
                    if ($applicableFine && $today->greaterThan($applicableFine->to_date)) {
                        $monthObj->late_fine = $applicableFine->amount;
                    }

                    $monthObj->total = $monthlyTotal + $monthObj->late_fine;
                    
                    if ($remainingPaid >= $monthObj->total) {
                        $monthObj->paid = $monthObj->total;
                        $monthObj->dues = 0;
                        $remainingPaid -= $monthObj->total;
                    } elseif ($remainingPaid > 0) {
                        $monthObj->paid = $remainingPaid;
                        $monthObj->dues = $monthObj->total - $remainingPaid;
                        $remainingPaid = 0;
                    } else {
                        $monthObj->paid = 0;
                        $monthObj->dues = $monthObj->total;
                    }
                    
                    $months[] = $monthObj;
                }
            }
        }

        return view('pages.fee.quick-collect', compact('student', 'sections', 'globalClasses', 'months'));
    }
}
