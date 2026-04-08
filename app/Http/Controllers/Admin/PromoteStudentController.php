<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Exception;

class PromoteStudentController extends Controller
{
    public function index()
    {
        // Load distinct classes and sections for the filter dropdowns.
        $classes = SchoolClass::orderBy('id')->get(); // Contains id and name
        $sections = Section::orderBy('name')->get(); // Contains id and name

        return view('pages.student.promote', compact('classes', 'sections'));
    }

    public function search(Request $request)
    {
        $class = $request->input('class');
        $section = $request->input('section');

        // Query students based on current class and section
        // Query students based on current class and section
        $students = Student::with(['classInfo', 'section'])->where('class_id', $class)
            ->where('section_id', $section)
            ->where('is_active', true)
            ->get();

        // Map the data for easier consumption in JS
        $data = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'sid' => 'S' . str_pad($student->id, 4, '0', STR_PAD_LEFT), // Pseudo SID
                'adm' => $student->admission_no ?? 'N/A',
                'name' => $student->student_name,
                'father' => $student->father_name ?? $student->parent?->father_name ?? 'N/A',
                'class' => $student->classInfo?->name ?? 'N/A',
                'section' => $student->section?->name ?? 'N/A',
                'roll' => $student->roll_no ?? 'N/A',
            ];
        });

        return response()->json([
            'success' => true,
            'students' => $data
        ]);
    }

    public function updateSingle(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:students,id',
            'new_class' => 'required|integer|exists:classes,id',
            'new_section' => 'required|integer|exists:sections,id',
            'new_roll' => 'nullable|string',
        ]);

        try {
            $student = Student::findOrFail($request->id);
            $student->class_id = $request->new_class;
            $student->section_id = $request->new_section;
            if ($request->filled('new_roll')) {
                $student->roll_no = $request->new_roll;
            }
            $student->save();

            return response()->json([
                'success' => true,
                'message' => "Student {$student->student_name} successfully promoted to Class {$request->new_class}-{$request->new_section}."
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to promote student. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAll(Request $request)
    {
        $request->validate([
            'students' => 'required|array',
            'students.*.id' => 'required|exists:students,id',
            'students.*.new_class' => 'required|integer|exists:classes,id',
            'students.*.new_section' => 'required|integer|exists:sections,id',
            'students.*.new_roll' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $updatedCount = 0;
            foreach ($request->students as $studentData) {
                // Ignore if class/section not provided
                if (empty($studentData['new_class']) || empty($studentData['new_section'])) {
                    continue;
                }

                $student = Student::find($studentData['id']);
                if ($student) {
                    $student->class_id = $studentData['new_class'];
                    $student->section_id = $studentData['new_section'];
                    if (!empty($studentData['new_roll'])) {
                        $student->roll_no = $studentData['new_roll'];
                    }
                    $student->save();
                    $updatedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully promoted {$updatedCount} students."
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform batch promotion. ' . $e->getMessage()
            ], 500);
        }
    }
}
