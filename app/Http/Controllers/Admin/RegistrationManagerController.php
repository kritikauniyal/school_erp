<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Registration;
use App\Models\RegistrationStudent;

class RegistrationManagerController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::with('students')->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('reg_no', 'like', "%{$keyword}%")
                  ->orWhere('father_name', 'like', "%{$keyword}%")
                  ->orWhere('mother_name', 'like', "%{$keyword}%")
                  ->orWhereHas('students', function ($sq) use ($keyword) {
                      $sq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_name')) {
            $className = $request->class_name;
            $query->whereHas('students', function ($q) use ($className) {
                $q->where('class', $className);
            });
        }

        if ($request->filled('from_date')) {
            $query->where('reg_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('reg_date', '<=', $request->to_date);
        }

        $registrations = $query->paginate(10)->withQueryString();

        return view('pages.enquiry.registration', compact('registrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'father_name' => 'required|string|max:255',
            'father_mobile' => 'required|string|max:20',
            'reg_date' => 'required|date',
            'status' => 'required|string',
            'students' => 'required|array|min:1',
            'students.*.name' => 'required|string|max:255',
            'students.*.class' => 'required|string|max:50',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Generate a unique REG00X number
            $lastReg = Registration::latest('id')->first();
            $nextId = $lastReg ? $lastReg->id + 1 : 1;
            $regNo = 'REG' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            $registration = Registration::create([
                'reg_no' => $regNo,
                'reg_date' => $request->reg_date,
                'status' => $request->status,
                'father_name' => $request->father_name,
                'father_mobile' => $request->father_mobile,
                'mother_name' => $request->mother_name,
                'mother_mobile' => $request->mother_mobile,
                'email' => $request->email,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'city' => $request->city,
                'remarks' => $request->remarks,
            ]);

            foreach ($request->students as $studentData) {
                $registration->students()->create($studentData);
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => true, 'message' => 'Registration created successfully', 'reg_id' => $registration->id]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $registration = Registration::with('students')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $registration]);
    }

    public function edit($id)
    {
        $registration = Registration::with('students')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $registration]);
    }

    public function update(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);
        
        $request->validate([
            'father_name' => 'required|string|max:255',
            'father_mobile' => 'required|string|max:20',
            'reg_date' => 'required|date',
            'status' => 'required|string',
            'students' => 'required|array|min:1',
            'students.*.name' => 'required|string|max:255',
            'students.*.class' => 'required|string|max:50',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $registration->update([
                'reg_date' => $request->reg_date,
                'status' => $request->status,
                'father_name' => $request->father_name,
                'father_mobile' => $request->father_mobile,
                'mother_name' => $request->mother_name,
                'mother_mobile' => $request->mother_mobile,
                'email' => $request->email,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'city' => $request->city,
                'remarks' => $request->remarks,
            ]);

            // Clear old students and create new
            $registration->students()->delete();
            foreach ($request->students as $studentData) {
                $registration->students()->create($studentData);
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => true, 'message' => 'Registration updated successfully']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        Registration::findOrFail($id)->delete();

        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Registration deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Registration deleted successfully.');
    }

    public function confirmAdmission($id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $registration = Registration::with('students')->findOrFail($id);
            
            // Check if it was already converted to avoid duplicates
            if ($registration->status === 'Converted to Admission') {
                if (request()->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'Registration has already been converted to an admission.']);
                }
                return redirect()->back()->with('error', 'Registration has already been converted to an admission.');
            }
            
            // Generate Admissions for each student attached to the registration
            foreach($registration->students as $student) {
                // Implementation details omitted for brevity but preserved in final
                $exists = \App\Models\Admission::where('registration_student_id', $student->id)->exists();
                if (!$exists) {
                    $lastAdm = \App\Models\Admission::latest('id')->first();
                    $nextId = $lastAdm ? $lastAdm->id + 1 : 1;
                    $admNo = 'ADM' . date('Y') . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                    
                    \App\Models\Admission::create([
                        'registration_id' => $registration->id,
                        'registration_student_id' => $student->id,
                        'admission_no' => $admNo,
                        'date' => now(),
                        'fee_collected' => 0,
                        'session' => date('Y') . '-' . (date('Y') + 1),
                        'status' => 'Pending Payment'
                    ]);
                }
            }
            
            $registration->update(['status' => 'Converted to Admission']);
            
            \Illuminate\Support\Facades\DB::commit();

            if (request()->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Registration successfully converted to Admission!']);
            }

            return redirect()->back()->with('success', 'Registration successfully converted to Admission! Students have been assigned Admission Numbers.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if (request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Error converting to admission: ' . $e->getMessage());
        }
    }

    /**
     * Lookup a registration by reg_no for auto-fill in student admission form.
     */
    public function lookup(Request $request)
    {
        $regNo = trim($request->get('reg_no', ''));
        if (!$regNo) {
            return response()->json(['success' => false, 'message' => 'Registration number required']);
        }

        // Search in the registrations table by reg_no
        $registration = Registration::with('students')->where('reg_no', $regNo)->first();

        if (!$registration) {
            return response()->json(['success' => false, 'message' => 'Registration not found']);
        }

        // Get first student from registration
        $student = $registration->students->first();

        return response()->json([
            'success' => true,
            'student' => [
                'id'               => $student ? $student->id : null,
                'student_name'     => $student ? $student->name : '',
                'name'             => $student ? $student->name : '',
                'dob'              => $student ? ($student->dob ?? '') : '',
                'date_of_birth'    => $student ? ($student->dob ?? '') : '',
                'gender'           => $student ? ($student->gender ?? 'Male') : 'Male',
                'class_name'       => $student ? ($student->class ?? '') : '',
                'class'            => $student ? ($student->class ?? '') : '',
                'mobile'           => $registration->father_mobile ?? '',
                'father_mobile'    => $registration->father_mobile ?? '',
                'father_name'      => $registration->father_name ?? '',
                'mother_name'      => $registration->mother_name ?? '',
                'mother_mobile'    => $registration->mother_mobile ?? '',
                'email'            => $registration->email ?? '',
                'address1'         => $registration->address1 ?? '',
                'address2'         => $registration->address2 ?? '',
                'city'             => $registration->city ?? '',
                'previous_school'  => $student ? ($student->previous_school ?? '') : '',
                'last_school'      => $student ? ($student->previous_school ?? '') : '',
            ]
        ]);
    }
}
