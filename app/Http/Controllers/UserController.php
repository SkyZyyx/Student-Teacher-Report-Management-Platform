<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\Section;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $user = User::first(); // Assuming you want to get the first user or change this as per your requirement
        return view('profile', compact('user'));
    }
    public function show_profile($id)
    {
        $user = User::findOrFail($id);
        return view('profileuser', compact('user'));
    }

    public function show()
    {
        $users = User::with(['section', 'group'])->get();
        $modules = Module::all();
        $sections = Section::all();
        $groupes = Group::all();

        return view('index', [
            'users' => $users,
            'modules' => $modules,
            'sections' => $sections,
            'groupes' => $groupes,
        ]);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|max:255',
            'section_id' => 'nullable|exists:sections,id',
            'groupe_id' => 'nullable|exists:groups,id',
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
        ]);

        $user = new User([
            'name' => $validatedData['nom'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
            'section_id' => $validatedData['section_id'] ?? null,
            'groupe_id' => $validatedData['groupe_id'] ?? null,
        ]);

        $user->save();

        if ($user->role === 'enseignant' && isset($validatedData['modules'])) {
            $user->modules()->sync($validatedData['modules']);
        }

        return redirect()->route('users.show')->with('success', 'User created successfully');
    }
    

    public function edit($id)
    {
        $user = User::find($id);
        $usersectiongroup = User::with(['section', 'group'])->get();
        $modules = Module::all();
        $sections = Section::all();
        $groupes = Group::all();
        $userModules = $user->modules()->pluck('module_id')->toArray(); // Get current user's modules
        return view('Admin.adminedit', compact('user','usersectiongroup','modules','sections','groupes','userModules'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255', // Ensure this is 'required' or 'nullable' based on your needs
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'module_id' => 'nullable|array', // This should be an array if multiple selections are allowed
            'module_id.*' => 'exists:modules,id', // Validate each module ID
            'section_id' => 'nullable|exists:sections,id',
            'groupe_id' => 'nullable|exists:groups,id',
        ]);
    
        // Find the user by ID
        $user = User::findOrFail($id);
    
        // Update the user's attributes
        $user->name = $validatedData['nom'];
        $user->email = $validatedData['email'];
        if (isset($validatedData['section_id'])) {
            $user->section_id = $validatedData['section_id'];
        }
        if (isset($validatedData['groupe_id'])) {
            $user->groupe_id = $validatedData['groupe_id'];
        }
        $user->save();
    
        // Update relationships
        if (isset($validatedData['module_id'])) {
            $user->modules()->sync($validatedData['module_id']);
        }
        $user->save();
    
        return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully');
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.show')->with('success', 'User deleted successfully');
    }

    public function updateImage(Request $request, User $user)
    {
        $request->validate(['image' => 'required|image']);

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('image')->store('profile_images', 'public');
        $user->update(['profile_image' => $path]);

        return back()->with('success', 'Image updated successfully');
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::findOrFail($id);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            // Update user's profile image
            $user->profile_image = '/uploads/' . $filename;
            $user->save();
        }

        return redirect()->route('profileuser', ['id' => $user->id])->with('success', 'Profile image updated successfully.');
    }

    public function search(Request $request)
    {
        $users = User::with(['section', 'group'])->get();
        $modules = Module::all();
        $sections = Section::all();
        $groupes = Group::all();

        $users = User::with(['section', 'group'])->get();
        $modules = Module::all();
        $sections = Section::all();
        $groupes = Group::all();

        $query = $request->input('search');
        $users = User::where('name', 'like', '%' . $query . '%')->get();

        return view('index',[
            'users' => $users,
            'modules' => $modules,
            'sections' => $sections,
            'groupes' => $groupes]);
    }
    public function showEnseignant()
    {
        $users = User::with(['section', 'group'])->get();
        $modules = Module::all();
        $sections = Section::all();
        $groupes = Group::all();

        return view('indexEnseignent', [
            'users' => $users,
            'modules' => $modules,
            'sections' => $sections,
            'groupes' => $groupes,
        ]);
    }
    public function showEtudiant()
    {
        $users = User::with(['section', 'group'])->get();
        $modules = Module::all();
        $sections = Section::all();
        $groupes = Group::all();

        return view('indexEtudiant', [
            'users' => $users,
            'modules' => $modules,
            'sections' => $sections,
            'groupes' => $groupes,
        ]);
    }

    
}
