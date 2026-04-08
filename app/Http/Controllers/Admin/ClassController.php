<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ClassRepository;

class ClassController extends Controller
{
    public function __construct(
        protected ClassRepository $classes
    ) {
    }

    /**
     * Display a listing of classes and their sections.
     */
    public function index()
    {
        $classes = \App\Models\SchoolClass::with('sections')->orderBy('numeric_value', 'asc')->get();
        // Since the UI design has tabs for Classes and Sections, we can just pass $classes and it already contains sections
        return view('classes.index', compact('classes'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'numeric_value' => 'nullable|integer'
        ]);

        \App\Models\SchoolClass::create([
            'name' => $request->name,
            'numeric_value' => $request->numeric_value ?? 0
        ]);

        return redirect()->back()->with('success', 'Class added successfully');
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'numeric_value' => 'nullable|integer'
        ]);

        $class = \App\Models\SchoolClass::findOrFail($id);
        $class->update([
            'name' => $request->name,
            'numeric_value' => $request->numeric_value ?? 0
        ]);

        return redirect()->back()->with('success', 'Class updated successfully');
    }

    public function destroy($id)
    {
        $class = \App\Models\SchoolClass::findOrFail($id);
        
        // Prevent deleting if it has students or sections
        if($class->sections()->count() > 0 || $class->students()->count() > 0) {
             return redirect()->back()->with('error', 'Cannot delete class. It has associated sections or students.');
        }

        $class->delete();
        return redirect()->back()->with('success', 'Class deleted successfully');
    }
}

