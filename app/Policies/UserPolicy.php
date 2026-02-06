<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * A "before" method that intercepts policy checks.
     * Allows any action if user is an admin or if no authentication is required.
     */
    public function before(User $user): bool|null
    {
        // When authentication is implemented, add admin check here
        // if ($user->is_admin) {
        //     return true;
        // }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Allow viewing all users for now (no authentication required in this challenge)
        // When authentication is implemented, add proper logic here
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, User $model): bool
    {
        // Allow viewing any user for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->id === $model->id || $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        // Allow creating users for now (no authentication required in this challenge)
        // When authentication is implemented, add proper logic here
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, User $model): bool
    {
        // Allow updating any user for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->id === $model->id || $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, User $model): bool
    {
        // Allow deleting any user for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(?User $user, User $model): bool
    {
        // Allow restoring any user for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, User $model): bool
    {
        // Allow force deleting any user for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->is_admin;
        return true;
    }
}
