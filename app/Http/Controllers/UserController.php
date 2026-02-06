<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Actions\User\DeleteUser;
use App\Actions\User\UpdateUser;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Actions\User\CreateUser;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;

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
    public function store(StoreUserRequest $request, CreateUser $createUser): JsonResponse
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

    public function search(Request $request): JsonResource
    {
        $query = User::with('positions.company');

        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('email') && $request->email) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('cpf') && $request->cpf) {
            $query->where('cpf', $request->cpf);
        }

        return UserResource::collection($query->paginate());
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
