<?php

namespace App\Policies;

use App\Models\Lick;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LickPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lick $lick): bool
    {
        return true; // all licks currently accesible
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lick $lick): bool
    {
        return $lick->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lick $lick): bool
    {
        return $lick->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lick $lick): bool
    {
        return $lick->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lick $lick): bool
    {
        return $lick->user_id === $user->id;
    }

    /**
     * Determine whether the user can share the Lick.
     */
    public function share(User $user, Lick $lick): bool
    {
        return $lick->user_id === $user->id;
    }
}
