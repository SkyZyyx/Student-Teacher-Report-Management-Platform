<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Determine where to redirect users after login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
     
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        $user = Auth::user();
    
        // Rediriger en fonction du rôle de l'utilisateur
        if ($user->role === 'admin') {
            return redirect()->route('users.show');
        } elseif ($user->role === 'enseignant') {
            return redirect()->route('Devoir.index');
        } elseif ($user->role === 'etudiant') {
            return redirect()->route('etudiantdevoirs.index');
        }
    
        // Redirection par défaut si aucun rôle spécifique n'est trouvé
        return redirect()->route('users.show');
    }
    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectAfterLogin(Request $request)
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect('/index');
            case 'etudiant':
                return redirect('/indexEtudiant');
            case 'enseignant':
                return redirect('/indexEnseignent');
            default:
                return redirect('/home'); // Default redirection
        }
    }
}
