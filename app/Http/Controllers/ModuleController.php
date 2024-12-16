<?php
namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Models\Group; // Assurez-vous d'importer le modèle Group
use App\Models\Section;

class ModuleController extends Controller
{
    // Méthode index pour afficher les sections et leurs groupes
    public function index()
    {
        $sections = Section::with('modules')->get();
        return view('Module.index', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'section_id' => 'required|exists:sections,id',
        ]);

        Module::create([
            'name' => $request->input('name'),
            'section_id' => $request->input('section_id'),
        ]);

        return redirect()->back()->with('success', 'Module ajouté avec succès');
    }
    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()->route('Module.index')
            ->with('success', 'Module deleted successfully');
    }
    
}
