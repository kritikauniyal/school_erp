<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelRoom;
use App\Models\HostelAllotment;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentHostelController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::all();
        $query = HostelAllotment::with(['student.user', 'student.classInfo', 'student.sectionInfo', 'student.parent', 'room.hostel']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('student', function($q) use ($search) {
                $q->where('registration_no', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('section_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('section_id', $request->section_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'alloted');
        }

        $allotments = $query->latest()->paginate(10);
        $rooms = HostelRoom::with('hostel')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.hostel.hostel-table-rows', compact('allotments'))->render(),
                'pagination' => (string) $allotments->links()
            ]);
        }

        return view('pages.hostel.student-hostel', compact('allotments', 'classes', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:hostel_rooms,id',
            'allotment_date' => 'required',
            'monthly_charge' => 'required|numeric'
        ]);

        // Check if student already has an active allotment
        $active = HostelAllotment::where('student_id', $request->student_id)
            ->where('status', 'alloted')
            ->first();

        if ($active) {
            return response()->json(['success' => false, 'message' => 'Student already has an active hostel allotment.']);
        }

        // Generate Allotment No
        $lastAllotment = HostelAllotment::latest()->first();
        $nextId = $lastAllotment ? $lastAllotment->id + 1 : 1;
        $allotmentNo = 'HOSTL' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        HostelAllotment::create([
            'allotment_no' => $allotmentNo,
            'student_id' => $request->student_id,
            'room_id' => $request->room_id,
            'allotment_date' => Carbon::parse($request->allotment_date)->format('Y-m-d'),
            'monthly_charge' => $request->monthly_charge,
            'status' => 'alloted',
            'remarks' => $request->remarks
        ]);

        return response()->json(['success' => true, 'message' => 'Hostel allotment created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required|exists:hostel_rooms,id',
            'allotment_date' => 'required',
            'monthly_charge' => 'required|numeric'
        ]);

        $allotment = HostelAllotment::findOrFail($id);
        $allotment->update([
            'room_id' => $request->room_id,
            'allotment_date' => Carbon::parse($request->allotment_date)->format('Y-m-d'),
            'monthly_charge' => $request->monthly_charge,
            'remarks' => $request->remarks
        ]);

        return response()->json(['success' => true, 'message' => 'Hostel allotment updated successfully.']);
    }

    public function stop($id)
    {
        $allotment = HostelAllotment::findOrFail($id);
        $allotment->update([
            'discharge_date' => now()->format('Y-m-d'),
            'status' => 'discharged'
        ]);

        return response()->json(['success' => true, 'message' => 'Hostel allotment stopped successfully.']);
    }

    public function searchStudents(Request $request)
    {
        $search = $request->search;
        $students = Student::with('user', 'classInfo', 'sectionInfo', 'parent')
            ->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhere('registration_no', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'reg_no' => $student->registration_no,
                    'class' => $student->classInfo->class_name ?? '',
                    'section' => $student->sectionInfo->section_name ?? '',
                    'father' => $student->parent->father_name ?? ''
                ];
            });

        return response()->json($students);
    }
}
