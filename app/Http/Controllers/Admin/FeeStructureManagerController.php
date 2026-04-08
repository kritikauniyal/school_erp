<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeStructureManagerController extends Controller
{
    public function index(Request $request)
    {
        $session = $request->input('session', '2025-2026');
        $classes = \App\Models\SchoolClass::orderBy('id')->get();
        // Load regular fee types with their applicable months
        $feeTypes = \App\Models\FeeType::where('is_admission_fee', false)->orderBy('id')->get();

        // Load existing active structures for these classes, fee types, and session
        $structures = [];
        if ($classes->isNotEmpty() && $feeTypes->isNotEmpty()) {
            $structures = \Illuminate\Support\Facades\DB::table('fee_structures')
                ->whereIn('class_name', $classes->pluck('name'))
                ->whereIn('fee_type_id', $feeTypes->pluck('id'))
                ->where('is_active', true)
                ->where('session', $session)
                ->get();
        }

        // Prepare amounts matrix (Single rate per class/feeType if applicable)
        $amounts = [];
        foreach ($classes as $cls) {
            foreach ($feeTypes as $ft) {
                $structure = collect($structures)->where('class_name', $cls->name)
                                        ->where('fee_type_id', $ft->id)
                                        ->first();
                
                if ($structure && $structure->monthly_amounts) {
                    $jsonAmounts = is_string($structure->monthly_amounts) ? json_decode($structure->monthly_amounts, true) : $structure->monthly_amounts;
                    // In the new simplified UI, we just show ONE amount. 
                    // We'll pick the first non-zero amount as the "rate"
                    $rate = 0;
                    if (is_array($jsonAmounts)) {
                        foreach ($jsonAmounts as $m => $val) {
                            if ($val > 0) {
                                $rate = $val;
                                break;
                            }
                        }
                    }
                    $amounts[$cls->name][$ft->id] = $rate;
                } else if ($structure) {
                    $amounts[$cls->name][$ft->id] = $structure->amount;
                } else {
                    $amounts[$cls->name][$ft->id] = $ft->default_amount;
                }
            }
        }

        return view('pages.fee.fee-structure-manager', compact('classes', 'feeTypes', 'amounts', 'session'));
    }

    public function storeFeeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0',
            'applicable_months' => 'required|array|min:1',
        ]);

        $feeType = \App\Models\FeeType::create([
            'name' => $validated['name'],
            'default_amount' => $validated['default_amount'],
            'applicable_months' => $validated['applicable_months'],
            'is_admission_fee' => false,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Fee type created.', 'feeType' => $feeType]);
    }

    public function updateFeeType(Request $request, $id)
    {
        $feeType = \App\Models\FeeType::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0',
            'applicable_months' => 'required|array|min:1',
        ]);

        $feeType->update($validated);

        return response()->json(['status' => 'success', 'message' => 'Fee type updated.', 'feeType' => $feeType]);
    }

    public function destroyFeeType($id)
    {
        $feeType = \App\Models\FeeType::findOrFail($id);
        $feeType->delete();

        return response()->json(['status' => 'success', 'message' => 'Fee type deleted.']);
    }

    public function saveStructure(Request $request)
    {
        $gridAmounts = $request->input('amounts'); // amounts[className][feeTypeId] = scalarRate
        $session = $request->input('session', '2025-2026');
        $effectiveFrom = $request->input('effective_from', date('Y-m-d'));
        
        if (!is_array($gridAmounts)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid data format.'], 400);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $feeTypes = \App\Models\FeeType::where('is_admission_fee', false)->get()->keyBy('id');
            $feeTypeIds = $feeTypes->keys()->toArray();

            foreach ($gridAmounts as $className => $fees) {
                foreach ($fees as $feeTypeId => $rate) {
                    if (in_array($feeTypeId, $feeTypeIds)) {
                        
                        $ft = $feeTypes[$feeTypeId];
                        $applicableMonths = is_array($ft->applicable_months) ? $ft->applicable_months : [];
                        
                        // Construct 12-month array (April to March = indices 0 to 11 in template, but we use 1-12 in DB for months probably? 
                        // Let's check previous usage. Previous usage was array_fill(1, 12, ...).
                        // So 1=Jan, 2=Feb, ... 12=Dec? 
                        // OR does it follow Academic year? 
                        // Academic Year: April (4), May (5), ..., Dec (12), Jan (1), Feb (2), March (3).
                        
                        // The template uses indices 0-11 for Apr-Mar.
                        // Let's map these to actual month numbers 1-12.
                        // Apr (0) -> 4, May (1) -> 5, ... Dec (8) -> 12, Jan (9) -> 1, Feb (10) -> 2, Mar (11) -> 3.
                        
                        $monthlyData = array_fill(1, 12, 0);
                        foreach ($applicableMonths as $idx) {
                            $monthNum = ($idx < 9) ? ($idx + 4) : ($idx - 8);
                            $monthlyData[$monthNum] = (float)$rate;
                        }

                        $annualTotal = array_sum($monthlyData);
                        $newMonthlyJson = json_encode($monthlyData);

                        $existing = \Illuminate\Support\Facades\DB::table('fee_structures')
                            ->where('class_name', $className)
                            ->where('fee_type_id', $feeTypeId)
                            ->where('session', $session)
                            ->where('is_active', true)
                            ->first();

                        if ($existing) {
                            if ($existing->monthly_amounts !== $newMonthlyJson) {
                                // Deactivate old
                                \Illuminate\Support\Facades\DB::table('fee_structures')
                                    ->where('id', $existing->id)
                                    ->update(['is_active' => false]);
                                
                                // Insert new record
                                $newId = \Illuminate\Support\Facades\DB::table('fee_structures')->insertGetId([
                                    'class_name' => $className,
                                    'fee_type_id' => $feeTypeId,
                                    'amount' => $annualTotal,
                                    'monthly_amounts' => $newMonthlyJson,
                                    'session' => $session,
                                    'effective_from' => $effectiveFrom,
                                    'is_active' => true,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);

                                // Audit Log (Update)
                                \App\Models\AuditLog::create([
                                    'user_id' => auth()->id() ?? 1,
                                    'action' => 'updated',
                                    'model_type' => 'App\Models\FeeStructure',
                                    'model_id' => $newId,
                                    'old_values' => ['monthly_amounts' => $existing->monthly_amounts, 'amount' => $existing->amount],
                                    'new_values' => ['monthly_amounts' => $newMonthlyJson, 'amount' => $annualTotal, 'class_name' => $className, 'fee_type' => $ft->name],
                                    'reason' => "Fee Structure for {$className} ({$ft->name}) updated to rate {$rate} via Manager"
                                ]);
                            }
                        } else {
                            $newId = \Illuminate\Support\Facades\DB::table('fee_structures')->insertGetId([
                                'class_name' => $className,
                                'fee_type_id' => $feeTypeId,
                                'amount' => $annualTotal,
                                'monthly_amounts' => $newMonthlyJson,
                                'session' => $session,
                                'effective_from' => $effectiveFrom,
                                'is_active' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            // Audit Log (Create)
                            \App\Models\AuditLog::create([
                                'user_id' => auth()->id() ?? 1,
                                'action' => 'created',
                                'model_type' => 'App\Models\FeeStructure',
                                'model_id' => $newId,
                                    'old_values' => null,
                                'new_values' => ['monthly_amounts' => $newMonthlyJson, 'amount' => $annualTotal, 'class_name' => $className, 'fee_type' => $ft->name],
                                'reason' => "Initial fee structure for {$className} ({$ft->name}) created with rate {$rate}"
                            ]);
                        }
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['status' => 'success', 'message' => 'All fee structure changes saved successfully for session '.$session]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function history(Request $request)
    {
        $logs = \App\Models\AuditLog::with('user')
            ->where('model_type', 'App\Models\FeeStructure')
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get()
            ->map(function($log) {
                $target = '';
                if(isset($log->new_values['class_name']) && isset($log->new_values['fee_type'])){
                    $target = $log->new_values['class_name'] . ' - ' . $log->new_values['fee_type'];
                }

                return [
                    'date' => $log->created_at->format('d M Y, h:i A'),
                    'user' => $log->user ? $log->user->name : 'System',
                    'action' => ucfirst($log->action),
                    'target' => $target,
                    'reason' => $log->reason,
                ];
            });

        return response()->json(['status' => 'success', 'history' => $logs]);
    }
}
