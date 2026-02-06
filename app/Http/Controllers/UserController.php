<?php

namespace App\Http\Controllers;

use App\Actions\User\{CreateUser, DeleteUser, UpdateUser};
use App\Http\Requests\{StoreUserRequest, UpdateUserRequest};
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(User::class, 'user');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {

        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, CreateUser $createUser)
    {
        $user = $createUser($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, UpdateUser $updateUser): UserResource
    {
        $user = $updateUser($user, $request->validated());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteUser $deleteUser): Response
    {
        $deleteUser($user);

        return response()->noContent();
    }
}
