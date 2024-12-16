<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compterendu;
use App\Models\Devoir;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompteRenduController extends Controller
{
    public function index()
    {
        // Retrieve all CompteRendus with their related Devoir and Users
        $compterendus = CompteRendu::with('devoir', 'users')->get();

        // Pass the data to the view
        return view('Compterendu.compterenduindextest', compact('compterendus'));
    }

    public function create(Request $request)
    {
        $devoir_id = $request->query('devoir_id');
        $students = User::where('role', 'etudiant')->get();

        return view('Compterendu.compterenducreate', compact('students', 'devoir_id'));
    }

  /*  public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'ressources' => 'nullable|string',
            'devoir_id' => 'required|exists:devoirs,id',
            'user_ids' => 'required|array|max:3', // Ensure max 3 students
            'user_ids.*' => 'exists:users,id',
        ]);

        $compteRendu = Compterendu::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'ressources' => $request->input('ressources'),
            'devoir_id' => $request->input('devoir_id'),
            'user_id' => auth()->id(),
        ]);

        // Attach the students to the compte rendu
        $compteRendu->users()->sync($request->input('user_ids'));

        return redirect()->route('compterendus.index')->with('success', 'Compte rendu créé avec succès.');
    }*/


    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|file|mimes:pdf,doc,docx',
            'ressources' => 'nullable|string',
            'devoir_id' => 'required|exists:devoirs,id',
            'user_ids' => 'required|array|max:2', // Ensure max 2 additional students
            'user_ids.*' => 'exists:users,id',
        ]);
    
        // Prepare user IDs for checking
        $userIds = $request->input('user_ids', []);
        $userIds[] = auth()->id(); // Add the current user to the list
    
        // Check if any of these users have already submitted a Compterendu for this Devoir
        $existingSubmission = Compterendu::whereHas('users', function ($query) use ($userIds) {
            $query->whereIn('user_id', $userIds);
        })->where('devoir_id', $request->input('devoir_id'))->exists();
    
        if ($existingSubmission) {
            return back()->withErrors(['error' => 'One of the users has already submitted a Compterendu for this Devoir.']);
        }
    
        // Handle the file upload
        if ($request->hasFile('content')) {
            $file = $request->file('content');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/compterendus'), $filename);
            $filePath = 'uploads/compterendus/' . $filename;
        } else {
            return back()->withErrors(['content' => 'File upload failed.']);
        }
    
        // Create the Compterendu instance
        $compteRendu = Compterendu::create([
            'title' => $request->input('title'),
            'content' => $filePath, // Store the file path as content
            'ressources' => $request->input('ressources'),
            'devoir_id' => $request->input('devoir_id'),
            'user_id' => auth()->id(), // Assuming there's a user_id field to store the creator
        ]);
    
        // Attach the students to the compte rendu
        $compteRendu->users()->sync($userIds);
       
    
        return redirect()->route('showcompterendu.devoir', ['id' => $compteRendu->devoir_id])->with('success', 'Compte rendu ajouté avec succès.');
    }



    public function show($id)
    {
        $compterendu =Compterendu::findOrFail($id);
        return view('Compterendu.compterendushow',compact('compterendu'));
    }

    public function edit(CompteRendu $compterendu)
    {
        $students = User::where('role', 'etudiant')->get();
        return view('Compterendu.CRedit', compact('compterendu', 'students'));
    }

    public function update(Request $request, CompteRendu $compterendu)
{
    // Validate the incoming request data
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'sometimes|file|mimes:pdf,doc,docx', // Changed to 'sometimes' to handle optional file updates
        'ressources' => 'nullable|string',
        'user_ids' => 'required|array|max:3', // Ensure max 3 students
        'user_ids.*' => 'exists:users,id',
    ]);

    // Prepare data for updating
    $data = [
        'title' => $request->input('title'),
        'ressources' => $request->input('ressources'),
    ];

    // Handle the file upload if a new file is provided
    if ($request->hasFile('content')) {
        $file = $request->file('content');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/compterendus'), $filename);
        $filePath = 'uploads/compterendus/' . $filename;
        $data['content'] = $filePath; // Update content path only if a new file is uploaded
    }

    // Update the Compterendu with new data
    $compterendu->update($data);

    // Sync the users associated with the Compterendu
    $compterendu->users()->sync($request->input('user_ids'));

    // Redirect to the index route with a success message
    return redirect()->route('compterendus.index')->with('success', 'Compte rendu mis à jour avec succès.');
}

    public function destroy(CompteRendu $compterendu)
    {
        $compterendu->delete();
        return redirect()->route('showcompterendu.devoir', ['id' => $compterendu->devoir_id])->with('success' , 'Compte rendu supprimé avec succès');
    }

    public function note(Compterendu $compterendu)
    {
        return view('Compterendu.show', compact('compterendu'));
    }

    public function grade(Request $request, CompteRendu $compterendu)
    {
        // Vérifier si une note existe déjà
        if (!is_null($compterendu->note)) {
            return redirect()->back()->withErrors(['error' => 'Une note a déjà été attribuée à ce compte rendu.']);
        }
    
        // Valider les données entrantes
        $request->validate([
            'note' => 'nullable|string|max:255', // Valider la note comme pouvant être nulle
            'comment' => 'nullable|string', // Valider le commentaire comme pouvant être nul
        ]);
    
        // Mettre à jour le compte rendu avec la nouvelle note et le commentaire
        $compterendu->update([
            'note' => $request->input('note'),
            'comment' => $request->input('comment'),
        ]);
    
        // Rediriger vers la page du devoir avec un message de succès
        return redirect()->route('showcompterendu.devoir', ['id' => $compterendu->devoir_id])
                         ->with('success', 'Compte rendu mis à jour avec succès.');
    }
    

   
}
