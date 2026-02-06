<?php

namespace App\Actions\User;

use App\Models\User;

class UpdateUser
{
    /**
     * Update the given user instance.
     *
     * @param  User  $user
     * @param  array<string, mixed>  $data
     */
    public function __invoke(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}
