<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group; // Assurez-vous d'importer le modèle Group
use App\Models\Section;

class GroupController extends Controller
{
    // Méthode index pour afficher les sections et leurs groupes
    public function index()
    {
        $sections = Section::with('groups')->get();
        return view('Group.index', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer',
            'section_id' => 'required|exists:sections,id',
        ]);

        Group::create([
            'number' => $request->input('number'),
            'section_id' => $request->input('section_id'),
        ]);

        return redirect()->back()->with('success', 'Groupe ajouté avec succès');
    }
    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->route('Group.index')
            ->with('success', 'Group deleted successfully');
    }
    
}
