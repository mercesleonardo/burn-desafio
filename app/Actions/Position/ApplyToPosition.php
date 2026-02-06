<?php

namespace App\Actions\Position;

use App\Models\Position;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ApplyToPosition
{
    public function __invoke(Position $position, int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        if ($position->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Usuário já se candidatou a esta posição.'], 400);
        }

        $position->users()->attach($user);

        return response()->json(['message' => 'Candidatura realizada com sucesso.'], 200);
    }
}
