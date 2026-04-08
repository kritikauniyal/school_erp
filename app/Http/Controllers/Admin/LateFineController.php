<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LateFine;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class LateFineController extends Controller
{
    public function index(Request $request)
    {
        $fines = LateFine::orderBy('from_date', 'desc')->get();
        if ($request->ajax() || $request->has('ajax')) {
            return view('pages.fee.late-fine-table', compact('fines'))->render();
        }
        $classes = SchoolClass::all(); 
        return view('pages.fee.late-fine', compact('fines', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classes' => 'required|array',
            'months' => 'required|array',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'amount' => 'required|numeric|min:0',
        ]);

        foreach ($request->months as $month) {
            LateFine::create([
                'classes' => $request->classes,
                'month' => $month,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'amount' => $request->amount,
                'is_active' => true,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Late fine settings saved successfully.']);
    }

    public function destroy($id)
    {
        LateFine::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Late fine setting deleted successfully.']);
    }
}
