<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Support\Facades\DB;

class AdmissionManagerController extends Controller
{
    public function show($id)
    {
        $admission = Admission::with(['registration', 'registrationStudent'])->findOrFail($id);
        return response()->json($admission);
    }
    
    public function index(Request $request)
    {
        $query = Admission::with(['registration', 'registrationStudent'])->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('admission_no', 'like', "%{$keyword}%")
                  ->orWhereHas('registrationStudent', function ($sq) use ($keyword) {
                      $sq->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('registration', function ($sq) use ($keyword) {
                      $sq->where('father_name', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $admissions = $query->paginate(15)->withQueryString();

        return view('pages.enquiry.admission-manager', compact('admissions'));
    }

    public function convertToStudent($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $admission = Admission::with(['registration', 'registrationStudent'])->findOrFail($id);

            if ($admission->status === 'Converted to Student') {
                return redirect()->back()->with('error', 'This admission has already been converted to a student.');
            }

            $reg = $admission->registration;
            $regStudent = $admission->registrationStudent;

            // Extract Fee
            $feeAmount = $request->input('fee_amount', 0);

            // Duplicate admission check
            $exists = Student::where('registration_student_id', $regStudent->id)->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'This registered student is already admitted as a permanent student record.');
            }

            $admission->update([
                'fee_collected' => $feeAmount,
                'status' => 'Converted to Student'
            ]);

            // Create Student
            $student = Student::create([
                'admission_no' => $admission->admission_no,
                'admission_date' => $admission->date,
                'registration_no' => $reg ? $reg->reg_no : null,
                'registration_student_id' => $regStudent->id,
                'session' => $admission->session,
                'class_id' => $regStudent->registration ? \App\Models\SchoolClass::where('name', $regStudent->class)->first()?->id : null,
                'student_name' => $regStudent->name,
                'gender' => $regStudent->gender,
                'dob' => $regStudent->dob,
                'address' => $reg ? $reg->address1 : null,
                'address_2' => $reg ? $reg->address2 : null,
                'place' => $reg ? $reg->city : null,
                'mobile' => $reg ? $reg->father_mobile : null,
                'email' => $reg ? $reg->email : null,
                'is_active' => true,
            ]);

            // Create Parent Info
            if ($reg) {
                StudentParent::create([
                    'student_id' => $student->id,
                    'father_name' => $reg->father_name,
                    'father_phone' => $reg->father_mobile,
                    'mother_name' => $reg->mother_name,
                    'mother_phone' => $reg->mother_mobile,
                    'father_email' => $reg->email,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Admission fee confirmed and successfully converted into a Student record!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to convert student: ' . $e->getMessage());
        }
    }
}
