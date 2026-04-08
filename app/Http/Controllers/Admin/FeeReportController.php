<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeReportController extends Controller
{
    public function index()
    {
        $classes = \App\Models\SchoolClass::all();
        return view('pages.fee.fee-report', compact('classes'));
    }

    public function getData(Request $request)
    {
        $type = $request->type;
        $data = [];

        switch ($type) {
            case 'classwise':
                $data = \App\Models\SchoolClass::withCount('students')->get()->map(function($class) {
                    $studentIds = $class->students->pluck('id');
                    
                    // Simple sum for example - in real system would use accounting service or sum ledgers
                    $totalFee = \App\Models\StudentFee::whereIn('student_id', $studentIds)->sum('total');
                    $collected = \App\Models\StudentLedger::whereIn('student_id', $studentIds)
                        ->where('transaction_type', 'Credit')
                        ->sum('amount');
                    
                    return [
                        'class' => $class->name,
                        'students' => $class->students_count,
                        'totalFee' => (float)$totalFee,
                        'collected' => (float)$collected,
                        'pending' => (float)($totalFee - $collected)
                    ];
                });
                break;

            case 'collection':
                $query = \App\Models\StudentLedger::with(['student.user', 'student.class'])
                    ->where('transaction_type', 'Credit');

                if ($request->filled('fromDate')) {
                    $query->whereDate('date', '>=', $request->fromDate);
                }
                if ($request->filled('toDate')) {
                    $query->whereDate('date', '<=', $request->toDate);
                }
                if ($request->filled('class')) {
                    $classId = \App\Models\SchoolClass::where('name', $request->class)->value('id');
                    $query->whereHas('student', function($q) use ($classId) {
                        $q->where('class_id', $classId);
                    });
                }

                $data = $query->get()->map(function($ledger) {
                    return [
                        'date' => $ledger->date->format('Y-m-d'),
                        'receiptNo' => 'REC-' . $ledger->id,
                        'student' => $ledger->student->user->name ?? 'N/A',
                        'class' => $ledger->student->class->name ?? 'N/A',
                        'amount' => (float)$ledger->amount,
                        'mode' => $ledger->description // Temporary mapping
                    ];
                });
                break;

            case 'ledger':
                $studentId = $request->studentId;
                if (!$studentId) {
                    // Try searching by name if ID not provided
                    if ($request->filled('search')) {
                        $studentId = \App\Models\Student::whereHas('user', function($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%");
                        })->value('id');
                    }
                }

                if ($studentId) {
                    $student = \App\Models\Student::with(['user', 'class', 'parent'])->find($studentId);
                    if ($student) {
                        $ledgers = \App\Models\StudentLedger::where('student_id', $studentId)
                            ->orderBy('date')
                            ->get();
                        
                        $data = [
                            'studentId' => $student->id,
                            'name' => $student->user->name ?? 'N/A',
                            'father' => $student->parent_name ?? 'N/A',
                            'class' => $student->class->name ?? 'N/A',
                            'year' => $request->session ?? '2025-2026',
                            'total' => (float)$ledgers->where('transaction_type', 'Credit')->sum('amount'),
                            'transactions' => $ledgers->map(function($l) {
                                return [
                                    'date' => $l->date->format('Y-m-d'),
                                    'type' => $l->transaction_type,
                                    'amount' => (float)$l->amount,
                                    'desc' => $l->description
                                ];
                            })
                        ];
                    }
                }
                break;

            case 'overall':
                // Monthly aggregation for the current session
                $session = '2025-2026';
                $months = ["April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March"];
                
                foreach ($months as $month) {
                    // This is a simplified mock-like logic until a proper billing table exists
                    $data[] = [
                        'month' => $month,
                        'collected' => (float)\App\Models\StudentLedger::where('session', $session)
                            ->where('transaction_type', 'Credit')
                            ->whereMonth('date', date('m', strtotime($month)))
                            ->sum('amount'),
                        'dues' => 0 // Would need detailed billing info
                    ];
                }
                break;

            case 'daily':
                $date = $request->date ?? date('Y-m-d');
                $ledgers = \App\Models\StudentLedger::with(['student.user', 'student.class'])
                    ->whereDate('date', $date)
                    ->where('transaction_type', 'Credit')
                    ->get();

                $data = $ledgers->map(function($l) {
                    return [
                        'date' => $l->date->format('Y-m-d'),
                        'receiptNo' => 'REC-' . $l->id,
                        'student' => $l->student->user->name ?? 'N/A',
                        'class' => $l->student->class->name ?? 'N/A',
                        'online' => str_contains(strtolower($l->description), 'online') ? (float)$l->amount : 0,
                        'offline' => !str_contains(strtolower($l->description), 'online') ? (float)$l->amount : 0,
                        'total' => (float)$l->amount
                    ];
                });
                break;
        }

        return response()->json($data);
    }
}
