<?php

namespace App\Policies;

use App\Models\Compterendu;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompteRenduPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Compterendu $compterendu)
    {
        return true; // Tout le monde peut voir, mais vous pouvez ajouter des restrictions supplémentaires si nécessaire
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role === 'etudiant';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Compterendu $compterendu)
    {
        return $user->role === 'etudiant' && $compterendu->users->contains($user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Compterendu $compterendu)
    {
        return $user->role === 'etudiant' && $compterendu->users->contains($user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    

    /**
     * Determine whether the user can permanently delete the model.
     */
    
    public function grade(User $user, Compterendu $compterendu)
    {
        return $user->role === 'enseignant';
    }
}
