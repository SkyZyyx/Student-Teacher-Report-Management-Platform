<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module; // Assurez-vous d'importer le modèle Group
use App\Models\User;
use App\Models\Devoir;


class DevoirController extends Controller
{
    // Méthode index pour afficher les sections et leurs groupes
    public function index()
    {
        $user = auth()->user();
        $modules = $user->modules()->with('devoirs')->get();
        return view('Devoir.index', compact('modules','user'));
    }
    public function indexEtudiant()
    {
        $user = auth()->user();

        if ($user->role === 'etudiant') {
            // Récupérer les modules liés à la section de l'étudiant avec leurs devoirs
            $modules = Module::where('section_id', $user->section_id)
                ->with('devoirs')
                ->get();
        } else {
            // Si l'utilisateur n'est pas un étudiant, afficher tous les modules avec leurs devoirs
            $modules = Module::with('devoirs')->get();
        }

        return view('devoir.indexEtudiant', compact('modules'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'ressources' => 'required|string',
            'module_id' => 'required|exists:modules,id',
            'due_date' => 'required|date',
        ]);

        Devoir::create([
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'ressources' => $request->input('ressources'),
            'module_id' => $request->input('module_id'),
            'due_date' => $request->input('due_date'),
        ]);

        return redirect()->back()->with('success', 'Devoir ajouté avec succès');
    }
    public function destroy(Devoir $devoir)
    {
        $devoir->delete();
    
        return redirect()->route('Devoir.index')
            ->with('success', 'Devoir deleted successfully');
    }
    
    public function show($devoir_id)
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
    
        // Récupérer le devoir par son ID
        $devoir = Devoir::findOrFail($devoir_id);

        // Récupérer le compte rendu associé à ce devoir et à l'utilisateur authentifié
        $compterendu = $devoir->compterendus()
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        // Compact les données pour les passer à la vue
        return view('Devoir.show', compact('compterendu', 'devoir'));
    }
    public function showProf($devoir_id)
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
    
        // Récupérer le devoir par son ID
        $devoir = Devoir::findOrFail($devoir_id);

        // Récupérer le compte rendu associé à ce devoir et à l'utilisateur authentifié
        $compterendu = $devoir->compterendus()
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        // Compact les données pour les passer à la vue
        return view('Devoir.showenseignant', compact('compterendu', 'devoir'));
    }

        public function getCompteRendu($devoir_id)
        {
            // Récupérer l'utilisateur authentifié
            $user = Auth::user();
    
            // Récupérer le devoir par son ID
            $devoir = Devoir::findOrFail($devoir_id);
    
            // Récupérer le compte rendu associé à ce devoir et à l'utilisateur authentifié
            $compteRendu = $devoir->compterendus()
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first();
    
            // Compact les données pour les passer à la vue
            return view('votre_vue', compact('compteRendu', 'devoir'));
        }


    public function showDevoirComptesRendus($id)
{
    $devoir = Devoir::with('compterendus','module')->findOrFail($id);
    return view('Compterendu.compterendudevoirindex', compact('devoir'));
}
    
}