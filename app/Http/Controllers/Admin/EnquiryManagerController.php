<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnquiryManagerController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Enquiry::query();
        
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('mobile', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('enq_no', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('for')) {
            $request->where('for', $request->for);
        }

        $enquiries = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('pages.enquiry.index', compact('enquiries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'date' => 'required|date',
            'followup_date' => 'nullable|date',
            'reference' => 'nullable|string',
            'status' => 'nullable|string',
            'for' => 'required|string',
            'class' => 'nullable|string',
            'no_of_child' => 'nullable|integer',
            'other' => 'nullable|string',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        // Generate Enquiry Number
        $latest = \App\Models\Enquiry::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $data['enq_no'] = 'ENQ-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        \App\Models\Enquiry::create($data);
        
        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Enquiry added successfully.']);
        }

        return redirect()->back()->with('success', 'Enquiry added successfully.');
    }

    public function show($id)
    {
        $enquiry = \App\Models\Enquiry::findOrFail($id);
        return response()->json($enquiry);
    }

    public function edit($id)
    {
        $enquiry = \App\Models\Enquiry::findOrFail($id);
        return response()->json($enquiry);
    }

    public function update(Request $request, $id)
    {
        $enquiry = \App\Models\Enquiry::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'date' => 'required|date',
            'followup_date' => 'nullable|date',
            'reference' => 'nullable|string',
            'status' => 'nullable|string',
            'for' => 'required|string',
            'class' => 'nullable|string',
            'no_of_child' => 'nullable|integer',
            'other' => 'nullable|string',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $enquiry->update($data);

        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Enquiry updated successfully.']);
        }

        return redirect()->back()->with('success', 'Enquiry updated successfully.');
    }

    public function destroy($id)
    {
        $enquiry = \App\Models\Enquiry::findOrFail($id);
        $enquiry->delete();

        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Enquiry deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Enquiry deleted successfully.');
    }

    public function followup(Request $request, $id)
    {
        $enquiry = \App\Models\Enquiry::findOrFail($id);
        $enquiry->update([
            'followup_date' => $request->followup_date,
            'remarks' => $request->remarks,
            'status' => 'Follow-up'
        ]);

        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Follow-up updated successfully.']);
        }

        return redirect()->back()->with('success', 'Follow-up updated successfully.');
    }
}
