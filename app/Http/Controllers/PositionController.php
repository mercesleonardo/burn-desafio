<?php

namespace App\Http\Controllers;

use App\Actions\Position\{ApplyToPosition, CreatePosition, DeletePosition, UpdatePosition};
use App\Http\Requests\{StorePositionRequest, UpdatePositionRequest};
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    /**
     * Lista todas as posições com paginação e cache para performance.
     * Utiliza cache Redis com tags para invalidação automática em mudanças.
     */
    public function index(Request $request): JsonResource
    {
        // Chave de cache dinâmica baseada nos parâmetros da requisição (para suporte futuro a filtros)
        $cacheKey = 'positions:index:' . md5(serialize($request->all()));

        // Cache com tags para invalidação granular
        $positions = Cache::tags(['positions'])->remember($cacheKey, 300, function () {
            return Position::with('company')->paginate();
        });

        return PositionResource::collection($positions);
    }

    public function store(StorePositionRequest $request, CreatePosition $createPosition): JsonResponse
    {
        $position = $createPosition($request->validated());

        // Invalida cache após criação para refletir mudanças
        Cache::tags(['positions'])->flush();

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

        // Invalida cache após atualização para refletir mudanças
        Cache::tags(['positions'])->flush();

        return (new PositionResource($position->load('company')))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Position $position, DeletePosition $deletePosition): JsonResponse
    {
        $deletePosition($position);

        // Invalida cache após exclusão para refletir mudanças
        Cache::tags(['positions'])->flush();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function apply(Request $request, Position $position, ApplyToPosition $applyToPosition): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $response = $applyToPosition($position, $request->user_id);

        // Invalida cache após aplicação, pois pode afetar listagens ou contadores futuros
        Cache::tags(['positions'])->flush();

        return $response;
    }
}
