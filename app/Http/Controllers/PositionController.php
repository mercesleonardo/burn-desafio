<?php

namespace App\Http\Controllers;

use App\Actions\Position\{CreatePosition, DeletePosition, UpdatePosition};
use App\Http\Requests\{StorePositionRequest, UpdatePositionRequest};
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    public function index(): JsonResource
    {
        return PositionResource::collection(Position::with('company')->paginate());
    }

    public function store(StorePositionRequest $request, CreatePosition $createPosition): JsonResponse
    {
        $position = $createPosition($request->validated());

        return (new PositionResource($position->load('company')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Position $position): PositionResource
    {
        return new PositionResource($position->load('company'));
    }

    public function update(UpdatePositionRequest $request, Position $position, UpdatePosition $updatePosition): JsonResponse
    {
        $position = $updatePosition($position, $request->validated());

        return (new PositionResource($position->load('company')))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Position $position, DeletePosition $deletePosition): JsonResponse
    {
        $deletePosition($position);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function apply(Request $request, Position $position): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = \App\Models\User::find($request->user_id);

        if ($position->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Usuário já se candidatou a esta posição.'], 400);
        }

        $position->users()->attach($user);

        return response()->json(['message' => 'Candidatura realizada com sucesso.'], 200);
    }
}
