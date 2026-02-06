<?php

namespace App\Actions\User;

use App\Events\UserCreated;
use App\Models\User;

class CreateUser
{
    /**
     * Create a new user instance.
     *
     * @param  array<string, mixed>  $data
     */
    public function __invoke(array $data): User
    {
        $user = User::create($data);

        UserCreated::dispatch($user);

        return $user;
    }
}
