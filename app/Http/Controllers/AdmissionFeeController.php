<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeType;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;

class AdmissionFeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('get_history')) {
            return $this->getHistory();
        }
        $session = $request->input('session', '2025-2026');
        $classes = SchoolClass::orderBy('id')->get();
        // Load only admission fee types
        $feeTypes = FeeType::where('is_admission_fee', true)->orderBy('id')->get();
        
        // Build a matrix of amounts: $amounts[$class_id][$fee_type_id]
        $amounts = [];
        
        if ($classes->isNotEmpty() && $feeTypes->isNotEmpty()) {
            // Load existing structures for these classes and fee types
            $structures = DB::table('fee_structures')
                ->whereIn('class_name', $classes->pluck('name'))
                ->whereIn('fee_type_id', $feeTypes->pluck('id'))
                ->where('is_active', true)
                ->where('session', $session)
                ->get();

            foreach ($classes as $cls) {
                foreach ($feeTypes as $ft) {
                    // Find if there's an existing structure
                    $structure = $structures->where('class_name', $cls->name)
                                            ->where('fee_type_id', $ft->id)
                                            ->first();
                    
                    // If not found, use the default amount from the fee type
                    $amounts[$cls->name][$ft->id] = $structure ? $structure->amount : $ft->default_amount;
                }
            }
        }

        return view('pages.fee.admission-fee-structure', compact('classes', 'feeTypes', 'amounts'));
    }

    public function storeFeeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            // Create fee type
            $feeType = FeeType::create([
                'name' => $validated['name'],
                'is_admission_fee' => true,
                'default_amount' => $validated['default_amount']
            ]);

            // Save default amounts for all classes to fee_structures
            $classes = SchoolClass::all();
            $monthlyData = array_fill(1, 12, $validated['default_amount']);
            $newMonthlyJson = json_encode($monthlyData);

            foreach ($classes as $cls) {
                DB::table('fee_structures')->insert([
                    'class_name' => $cls->name,
                    'fee_type_id' => $feeType->id,
                    'amount' => $validated['default_amount'],
                    'monthly_amounts' => $newMonthlyJson,
                    'session' => '2025-2026',
                    'effective_from' => date('Y-m-d'),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success', 
                'message' => 'Fee Type created successfully.',
                'fee_type' => $feeType
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateFeeType(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0'
        ]);

        $feeType = FeeType::where('is_admission_fee', true)->findOrFail($id);
        $feeType->update([
            'name' => $validated['name'],
            'default_amount' => $validated['default_amount']
        ]);

        // Note: updating the default amount here won't override existing fee_structures.
        // The user must click "Save All Changes" on the grid to update the actual class amounts.

        return response()->json([
            'status' => 'success', 
            'message' => 'Fee Type updated successfully.',
            'fee_type' => $feeType
        ]);
    }

    public function destroyFeeType($id)
    {
        $feeType = FeeType::where('is_admission_fee', true)->findOrFail($id);
        $feeType->delete(); // This should cascade to fee_structures if foreign key is set up with onDelete('cascade')

        return response()->json(['status' => 'success', 'message' => 'Fee Type deleted successfully.']);
    }

    public function saveStructure(Request $request)
    {
        
        $amounts = $request->input('amounts'); // Expected format: ['Class I' => [fee_type_id => amount, ...], ...]
        
        // Handle cases where the JSON payload wasn't automatically parsed into an array
        if (is_string($amounts)) {
            $amounts = json_decode($amounts, true);
        }
        if (is_object($amounts)) {
            $amounts = json_decode(json_encode($amounts), true);
        }

        $session = $request->input('session', '2025-2026');
        $effectiveFrom = $request->input('effective_from', date('Y-m-d'));
        
        if (empty($amounts)) {
            return response()->json(['status' => 'success', 'message' => 'No fee structures to save.']);
        }

        if (!is_array($amounts)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid data format.'], 400);
        }

        try {
            DB::beginTransaction();

            $feeTypeIds = FeeType::where('is_admission_fee', true)->pluck('id')->toArray();

            foreach ($amounts as $className => $fees) {
                foreach ($fees as $feeTypeId => $amount) {
                    if (in_array($feeTypeId, $feeTypeIds)) {
                        $existing = DB::table('fee_structures')
                            ->where('class_name', $className)
                            ->where('fee_type_id', $feeTypeId)
                            ->where('session', $session)
                            ->where('is_active', true)
                            ->first();

                        if ($existing) {
                            if ($existing->amount != $amount || $existing->effective_from != $effectiveFrom) {
                                // Deactivate old
                                DB::table('fee_structures')
                                    ->where('id', $existing->id)
                                    ->update(['is_active' => false]);
                                
                                $monthlyData = array_fill(1, 12, $amount);
                                $newMonthlyJson = json_encode($monthlyData);

                                // Insert new record entirely
                                $newId = DB::table('fee_structures')->insertGetId([
                                    'class_name' => $className,
                                    'fee_type_id' => $feeTypeId,
                                    'amount' => $amount,
                                    'monthly_amounts' => $newMonthlyJson,
                                    'session' => $session,
                                    'effective_from' => $effectiveFrom,
                                    'is_active' => true,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);

                                \App\Models\AuditLog::create([
                                    'user_id' => auth()->id() ?? 1,
                                    'action' => 'updated',
                                    'model_type' => 'App\Models\FeeStructure',
                                    'model_id' => $newId,
                                    'old_values' => ['amount' => $existing->amount, 'effective_from' => $existing->effective_from],
                                    'new_values' => ['amount' => $amount, 'effective_from' => $effectiveFrom, 'class_name' => $className, 'fee_type_id' => $feeTypeId],
                                    'reason' => "Admission Fee Structure for {$className} updated via Admission Fee Manager"
                                ]);
                            }
                        } else {
                            $monthlyData = array_fill(1, 12, $amount);
                            $newMonthlyJson = json_encode($monthlyData);

                            $newId = DB::table('fee_structures')->insertGetId([
                                'class_name' => $className,
                                'fee_type_id' => $feeTypeId,
                                'amount' => $amount,
                                'monthly_amounts' => $newMonthlyJson,
                                'session' => $session,
                                'effective_from' => $effectiveFrom,
                                'is_active' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            \App\Models\AuditLog::create([
                                'user_id' => auth()->id() ?? 1,
                                'action' => 'created',
                                'model_type' => 'App\Models\FeeStructure',
                                'model_id' => $newId,
                                'old_values' => null,
                                'new_values' => ['amount' => $amount, 'effective_from' => $effectiveFrom, 'class_name' => $className, 'fee_type_id' => $feeTypeId],
                                'reason' => "Initial Admission Fee Structure for {$className} created"
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            // Return success response
            return response()->json(['status' => 'success', 'message' => 'Fee structure saved for session '.$session.' effectively from ' . $effectiveFrom]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function getStructureByClass($className, Request $request)
    {
        $session = $request->input('session', '2025-2026');
        
        $structures = DB::table('fee_structures')
            ->join('fee_types', 'fee_structures.fee_type_id', '=', 'fee_types.id')
            ->where('fee_structures.class_name', $className)
            ->where('fee_structures.session', $session)
            ->where('fee_structures.is_active', true)
            ->where('fee_types.is_admission_fee', true)
            ->select('fee_types.name', 'fee_structures.amount')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $structures
        ]);
    }

    public function getHistory()
    {
        $logs = \App\Models\AuditLog::with('user')
            ->where('model_type', 'App\Models\FeeStructure')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }
}
