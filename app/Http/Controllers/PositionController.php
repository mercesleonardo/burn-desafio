<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\PositionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\{StorePositionRequest, UpdatePositionRequest};
use App\Actions\Position\{ApplyToPosition, CreatePosition, DeletePosition, UpdatePosition};

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

    public function apply(Request $request, Position $position, ApplyToPosition $applyToPosition): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        return $applyToPosition($position, $request->user_id);
    }
}
