<?php

namespace App\Policies;

use App\Models\Company;

class CompanyPolicy
{
    /**
     * A "before" method that intercepts policy checks.
     * Allows any action if user is an admin or if no authentication is required.
     */
    public function before(?Company $user): bool|null
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
    public function viewAny(?Company $user): bool
    {
        // Allow viewing all companies for now (no authentication required in this challenge)
        // When authentication is implemented, add proper logic here
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?Company $user, Company $model): bool
    {
        // Allow viewing any company for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->id === $model->id || $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?Company $user): bool
    {
        // Allow creating companies for now (no authentication required in this challenge)
        // When authentication is implemented, add proper logic here
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?Company $user, Company $model): bool
    {
        // Allow updating any company for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->id === $model->id || $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Company $user, Company $model): bool
    {
        // Allow deleting any company for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(?Company $user, Company $model): bool
    {
        // Allow restoring any company for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?Company $user, Company $model): bool
    {
        // Allow force deleting any company for now (no authentication required in this challenge)
        // When authentication is implemented, add logic like:
        // return $user->is_admin;
        return true;
    }
}
