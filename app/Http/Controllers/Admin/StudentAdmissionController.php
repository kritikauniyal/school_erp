<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentAdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Student::with('classInfo', 'sectionInfo')->latest();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('student_name', 'like', "%{$keyword}%")
                  ->orWhere('admission_no', 'like', "%{$keyword}%")
                  ->orWhere('registration_no', 'like', "%{$keyword}%");
            });
        }

        $students = $query->paginate(20)->withQueryString();
        $classes = \App\Models\SchoolClass::all();
        $sections = \App\Models\Section::all();
        
        // Only show registrations that haven't been fully admitted yet
        // A registration is fully admitted if all its students have been admitted? Or check by student specifically.
        // Let's show all registration students for now, but mark them? 
        // Actually the user said "jo data registration me h uska adm ho jaye to dubara use ka adm na ho".
        // So we filter:
        $admittedRegStudentIds = \App\Models\Student::whereNotNull('registration_student_id')->pluck('registration_student_id')->toArray();
        $registrations = \App\Models\RegistrationStudent::with('registration')
            ->whereHas('registration', function($q) {
                $q->where('status', 'Converted to Admission');
            })
            ->get()
            ->filter(function($regStudent) use ($admittedRegStudentIds) {
                return !in_array($regStudent->id, $admittedRegStudentIds);
            });

        $qualifications = ['Below 10th', '10th Pass', '12th Pass', 'Graduate', 'Post Graduate', 'Doctorate', 'Other'];
        $occupations = ['Agriculture', 'Business', 'Govt. Service', 'Private Service', 'Self Employed', 'Daily Wager', 'Housewife', 'Other'];
        $castes = ['General', 'OBC', 'SC', 'ST', 'Other'];
        $bloodGroups = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'Unknown'];

        $admissionFeeTypes = \DB::table('fee_types')->where('is_admission_fee', true)->get();

        return view('pages.student.admission', compact('students', 'classes', 'sections', 'registrations', 'qualifications', 'occupations', 'castes', 'bloodGroups', 'admissionFeeTypes'));
    }

    public function store(Request $request)
    {
        $regStudentId = $request->input('basic.registration_student_id');
        if ($regStudentId) {
            $exists = \App\Models\Student::where('registration_student_id', $regStudentId)->exists();
            if ($exists) {
                return response()->json(['success' => false, 'message' => 'This registered student is already admitted.'], 422);
            }
        }
        
        $regNo = $request->input('basic.registration_no');
        
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Create Student
            $student = \App\Models\Student::create([
                'admission_no' => $request->input('basic.admission_no'),
                'admission_date' => $request->input('basic.admission_date'),
                'registration_no' => $request->input('basic.registration_no'),
                'registration_student_id' => $request->input('basic.registration_student_id'),
                'class_id' => $request->input('basic.class_id'),
                'section_id' => $request->input('basic.section_id'),
                'roll_no' => $request->input('basic.roll_no'),
                'session' => $request->input('basic.session'),
                'medium' => $request->input('basic.medium'),
                'stream' => $request->input('basic.stream'),
                'house' => $request->input('basic.house'),
                'student_name' => $request->input('basic.student_name'),
                'gender' => $request->input('basic.gender'),
                'dob' => $request->input('basic.dob'),
                'blood_group' => $request->input('basic.blood_group'),
                'nationality' => $request->input('basic.nationality', 'Indian'),
                'religion' => $request->input('basic.religion'),
                'caste' => $request->input('basic.caste'),
                'category' => $request->input('basic.category'),
                'phone' => $request->input('basic.phone'),
                'mobile' => $request->input('basic.mobile'),
                'email' => $request->input('basic.email'),
                'aadhar_no' => $request->input('basic.aadhar_no'),
                'papan_no' => $request->input('basic.papan_no'),
                'apaair_id' => $request->input('basic.apaair_id'),
                'pen_no' => $request->input('basic.pen_no'),
                'physical_status' => $request->input('basic.physical_status'),
                'identity_mark' => $request->input('basic.identity_mark'),
                'account_head' => $request->input('basic.account_head'),
                'address' => $request->input('basic.address'),
                'address_2' => $request->input('basic.address_2'),
                'place' => $request->input('basic.place'),
                'pin_code' => $request->input('basic.pin_code'),
                'remarks' => $request->input('basic.remarks'),
                'rte_student' => $request->input('basic.rte_student') === 'on' ? 1 : 0,
                'is_active' => $request->input('basic.is_active', 1),
            ]);

            // 2. Create Parent Info
            if ($request->has('parent')) {
                \App\Models\StudentParent::create([
                    'student_id' => $student->id,
                    'father_name' => $request->input('parent.father_name'),
                    'father_qualification' => $request->input('parent.father_qualification'),
                    'father_income' => $request->input('parent.father_income'),
                    'father_occupation' => $request->input('parent.father_occupation'),
                    'father_aadhar' => $request->input('parent.father_aadhar'),
                    
                    'mother_name' => $request->input('parent.mother_name'),
                    'mother_qualification' => $request->input('parent.mother_qualification'),
                    'mother_income' => $request->input('parent.mother_income'),
                    'mother_occupation' => $request->input('parent.mother_occupation'),
                    'mother_aadhar' => $request->input('parent.mother_aadhar'),
                ]);
            }

            // 3. Create Previous School Info
            if ($request->has('previous')) {
                \App\Models\StudentPreviousSchool::create([
                    'student_id' => $student->id,
                    'school_name' => $request->input('previous.school_name'),
                    'previous_class' => $request->input('previous.class'),
                    'tc_no' => $request->input('previous.tc_no'),
                    
                    'bcg' => $request->input('previous.bcg') === 'on' ? 1 : 0,
                    'opv' => $request->input('previous.opv') === 'on' ? 1 : 0,
                    'opv_booster' => $request->input('previous.opv_booster') === 'on' ? 1 : 0,
                    'mmr' => $request->input('previous.mmr') === 'on' ? 1 : 0,
                    'dpt' => $request->input('previous.dpt') === 'on' ? 1 : 0,
                    'dpt_booster' => $request->input('previous.dpt_booster') === 'on' ? 1 : 0,
                    'measles' => $request->input('previous.measles') === 'on' ? 1 : 0,
                    'thyroid' => $request->input('previous.thyroid') === 'on' ? 1 : 0,
                    'hepatitis_b' => $request->input('previous.hepatitis_b') === 'on' ? 1 : 0,
                ]);
            }

            // 4. Save Photo if uploaded
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('student_photos', 'public');
                $student->photo_path = $path;
                $student->save();
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json(['success' => true, 'message' => 'Student admitted successfully']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $student = \App\Models\Student::with(['classInfo', 'sectionInfo', 'parent', 'previousSchool'])->findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $student = \App\Models\Student::findOrFail($id);
            $student->update([
                'admission_no' => $request->input('basic.admission_no'),
                'admission_date' => $request->input('basic.admission_date'),
                'registration_no' => $request->input('basic.registration_no'),
                'registration_student_id' => $request->input('basic.registration_student_id'),
                'session' => $request->input('basic.session'),
                'class_id' => $request->input('basic.class_id'),
                'section_id' => $request->input('basic.section_id'),
                'roll_no' => $request->input('basic.roll_no'),
                'medium' => $request->input('basic.medium'),
                'stream' => $request->input('basic.stream'),
                'house' => $request->input('basic.house'),
                'student_name' => $request->input('basic.student_name'),
                'gender' => $request->input('basic.gender'),
                'dob' => $request->input('basic.dob'),
                'blood_group' => $request->input('basic.blood_group'),
                'nationality' => $request->input('basic.nationality', 'Indian'),
                'religion' => $request->input('basic.religion'),
                'caste' => $request->input('basic.caste'),
                'category' => $request->input('basic.category'),
                'phone' => $request->input('basic.phone'),
                'mobile' => $request->input('basic.mobile'),
                'email' => $request->input('basic.email'),
                'aadhar_no' => $request->input('basic.aadhar_no'),
                'papan_no' => $request->input('basic.papan_no'),
                'apaair_id' => $request->input('basic.apaair_id'),
                'pen_no' => $request->input('basic.pen_no'),
                'physical_status' => $request->input('basic.physical_status'),
                'identity_mark' => $request->input('basic.identity_mark'),
                'account_head' => $request->input('basic.account_head'),
                'address' => $request->input('basic.address'),
                'address_2' => $request->input('basic.address_2'),
                'place' => $request->input('basic.place'),
                'pin_code' => $request->input('basic.pin_code'),
                'remarks' => $request->input('basic.remarks'),
                'rte_student' => $request->input('basic.rte_student') === 'on' ? 1 : 0,
                'is_active' => $request->input('basic.is_active', 1),
            ]);

            if ($request->has('parent')) {
                \App\Models\StudentParent::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'father_name' => $request->input('parent.father_name'),
                        'father_qualification' => $request->input('parent.father_qualification'),
                        'father_income' => $request->input('parent.father_income'),
                        'father_occupation' => $request->input('parent.father_occupation'),
                        'father_aadhar' => $request->input('parent.father_aadhar'),
                        
                        'mother_name' => $request->input('parent.mother_name'),
                        'mother_qualification' => $request->input('parent.mother_qualification'),
                        'mother_income' => $request->input('parent.mother_income'),
                        'mother_occupation' => $request->input('parent.mother_occupation'),
                        'mother_aadhar' => $request->input('parent.mother_aadhar'),
                    ]
                );
            }

            if ($request->has('previous')) {
                \App\Models\StudentPreviousSchool::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'school_name' => $request->input('previous.school_name'),
                        'previous_class' => $request->input('previous.class'),
                        'tc_no' => $request->input('previous.tc_no'),
                        
                        'bcg' => $request->input('previous.bcg') === 'on' ? 1 : 0,
                        'opv' => $request->input('previous.opv') === 'on' ? 1 : 0,
                        'opv_booster' => $request->input('previous.opv_booster') === 'on' ? 1 : 0,
                        'mmr' => $request->input('previous.mmr') === 'on' ? 1 : 0,
                        'dpt' => $request->input('previous.dpt') === 'on' ? 1 : 0,
                        'dpt_booster' => $request->input('previous.dpt_booster') === 'on' ? 1 : 0,
                        'measles' => $request->input('previous.measles') === 'on' ? 1 : 0,
                        'thyroid' => $request->input('previous.thyroid') === 'on' ? 1 : 0,
                        'hepatitis_b' => $request->input('previous.hepatitis_b') === 'on' ? 1 : 0,
                    ]
                );
            }

            if ($request->hasFile('photo')) {
                if ($student->photo_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($student->photo_path);
                }
                $path = $request->file('photo')->store('student_photos', 'public');
                $student->photo_path = $path;
                $student->save();
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
            $student = \App\Models\Student::findOrFail($id);
            if ($student->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->photo_path);
            }
            $student->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Student deleted successfully.']);
            }

            return redirect()->back()->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete student: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed to delete student.');
        }
    }

    public function getFees($id)
    {
        $student = \App\Models\Student::findOrFail($id);
        return response()->json([
            'success' => true,
            'fees' => $student->studentFees
        ]);
    }

    public function saveFees(Request $request, $id)
    {
        try {
            $student = \App\Models\Student::findOrFail($id);
            
            $request->validate([
                'fees' => 'array',
                'fees.*.name' => 'required|string',
                'fees.*.amount' => 'required|numeric',
                'fees.*.concession' => 'required|numeric',
            ]);

            \Illuminate\Support\Facades\DB::beginTransaction();

            $accounting = app(\App\Services\AccountingService::class);
            $oldFees = $student->studentFees()->get()->keyBy('fee_name');
            $newFeeNames = [];

            if ($request->has('fees') && is_array($request->fees)) {
                foreach ($request->fees as $fee) {
                    $feeName = $fee['name'];
                    $amount = floatval($fee['amount'] ?? 0);
                    $concession = floatval($fee['concession'] ?? 0);
                    $total = $amount - $concession;
                    $paid = floatval($fee['paid'] ?? $total);
                    $newFeeNames[] = $feeName;
                    
                    if ($oldFees->has($feeName)) {
                        $existing = $oldFees->get($feeName);
                        if ($existing->total != $total) {
                            // Amount changed: Reverse old, add new
                            $accounting->addLedgerEntry($student->id, now(), 'Reversal', $existing->total, get_class($existing), $existing->id, "Reversed modified fee: {$feeName}");
                            $accounting->addLedgerEntry($student->id, now(), 'Fee', $total, get_class($existing), $existing->id, "Modified Fee Assessed: {$feeName}");
                            
                            $existing->update([
                                'amount' => $amount, 'concession' => $concession, 'total' => $total, 'paid' => $paid
                            ]);
                        } else {
                            // Amount identical, update silently
                            $existing->update([
                                'amount' => $amount, 'concession' => $concession, 'total' => $total, 'paid' => $paid
                            ]);
                        }
                    } else {
                        // Completely new fee
                        $newFee = $student->studentFees()->create([
                            'fee_name' => $feeName,
                            'amount' => $amount, 'concession' => $concession, 'total' => $total, 'paid' => $paid,
                        ]);
                        $accounting->addLedgerEntry($student->id, now(), 'Fee', $total, get_class($newFee), $newFee->id, "Fee Assessed: {$feeName}");
                        
                        if ($paid > 0) {
                            $accounting->addLedgerEntry($student->id, now(), 'Payment', $paid, get_class($newFee), $newFee->id, "Paid at Admission: {$feeName}");
                        }
                    }
                }
            }

            // Identify and reverse removed fees
            foreach ($oldFees as $feeName => $existing) {
                if (!in_array($feeName, $newFeeNames)) {
                    $accounting->addLedgerEntry($student->id, now(), 'Reversal', $existing->total, get_class($existing), $existing->id, "Reversed deleted fee: {$feeName}");
                    $existing->delete();
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json(['success' => true, 'message' => 'Fees updated successfully']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
