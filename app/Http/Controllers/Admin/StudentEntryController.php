<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentEntryController extends Controller
{
    public function index()
    {
        $students = \App\Models\Student::with('classInfo', 'sectionInfo', 'parent')
            ->latest()
            ->paginate(5);
            
        $classes = \App\Models\SchoolClass::all();
        $sections = \App\Models\Section::all();
        
        return view('pages.student.entry', compact('students', 'classes', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'registration_no' => 'required|string|max:255',
            'admission_no' => 'required|string|max:255',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $student = \App\Models\Student::create([
                'student_name' => $request->student_name,
                'registration_no' => $request->registration_no,
                'admission_no' => $request->admission_no,
                'admission_date' => $request->admission_date,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'roll_no' => $request->roll_no,
                'category' => $request->category,
                'religion' => $request->religion,
                'caste' => $request->caste,
                // 'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'place' => $request->city,
                'pin_code' => $request->pin,
                'email' => $request->email,
                'mobile' => $request->student_mobile,
                'is_active' => true,
            ]);

            if ($request->father_name || $request->mother_name || $request->guardian_name) {
                \App\Models\StudentParent::create([
                    'student_id' => $student->id,
                    'father_name' => $request->father_name,
                    // 'father_phone' => $request->father_mobile,
                    'mother_name' => $request->mother_name,
                    // 'mother_phone' => $request->mother_mobile,
                    'guardian_name' => $request->guardian_name,
                    // 'guardian_phone' => $request->guardian_mobile,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => true, 'message' => 'Student added successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $student = \App\Models\Student::with(['classInfo', 'sectionInfo', 'parent'])->findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'registration_no' => 'required|string|max:255',
            'admission_no' => 'required|string|max:255',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $student = \App\Models\Student::findOrFail($id);
            $student->update([
                'student_name' => $request->student_name,
                'registration_no' => $request->registration_no,
                'admission_no' => $request->admission_no,
                'admission_date' => $request->admission_date,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'roll_no' => $request->roll_no,
                'category' => $request->category,
                'religion' => $request->religion,
                'caste' => $request->caste,
                // 'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'place' => $request->city,
                'pin_code' => $request->pin,
                'email' => $request->email,
                'mobile' => $request->student_mobile,
            ]);

            $parent = \App\Models\StudentParent::where('student_id', $student->id)->first();
            if ($parent) {
                $parent->update([
                    'father_name' => $request->father_name,
                    // 'father_phone' => $request->father_mobile,
                    'mother_name' => $request->mother_name,
                    // 'mother_phone' => $request->mother_mobile,
                    'guardian_name' => $request->guardian_name,
                    // 'guardian_phone' => $request->guardian_mobile,
                ]);
            } else if ($request->father_name || $request->mother_name || $request->guardian_name) {
                \App\Models\StudentParent::create([
                    'student_id' => $student->id,
                    'father_name' => $request->father_name,
                    'father_phone' => $request->father_mobile,
                    'mother_name' => $request->mother_name,
                    'mother_phone' => $request->mother_mobile,
                    'guardian_name' => $request->guardian_name,
                    'guardian_phone' => $request->guardian_mobile,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => true, 'message' => 'Student updated successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $student = \App\Models\Student::findOrFail($id);
            \App\Models\StudentParent::where('student_id', $student->id)->delete();
            // Optional: Handle fees / previous school if necessary. Let's rely on cascading deletes if configured, or manual delete.
            $student->delete();
            \Illuminate\Support\Facades\DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Student deleted successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
