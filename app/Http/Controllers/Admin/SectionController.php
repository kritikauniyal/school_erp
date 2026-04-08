<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ]);

        \App\Models\Section::create([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Section added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ]);

        $section = \App\Models\Section::findOrFail($id);
        $section->update([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Section updated successfully');
    }

    public function destroy($id)
    {
        $section = \App\Models\Section::findOrFail($id);

        if ($section->students()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete section. It has associated students.');
        }

        $section->delete();
        return redirect()->back()->with('success', 'Section deleted successfully');
    }
}
