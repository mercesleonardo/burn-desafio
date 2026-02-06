<?php

namespace App\Actions\User;

use App\Models\User;

class DeleteUser
{
    /**
     * Delete the given user instance.
     *
     * @param  User  $user
     */
    public function __invoke(User $user): void
    {
        $user->delete();
    }
}
