<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
     public function index()
    {
        $sections = Section::all();
        return view('Section.sections', compact('sections'));
    }

    public function create()
    {
        return view('Section.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    Section::create($request->all());

    return redirect()->route('sections.index')->with('success', 'Section created successfully.');
}
public function destroy($id)
{
    $section = Section::findOrFail($id);
    $section->delete();
    return redirect()->route('sections.index')->with('success', 'Section deleted successfully.');
}

public function search(Request $request)
{
    $query = $request->input('search');
    $section = Section::where('name', 'like', '%' . $query . '%')->get();
    return view('Section.sections', compact('sections'));
}

}
