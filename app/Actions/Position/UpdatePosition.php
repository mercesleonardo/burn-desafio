<?php

namespace App\Actions\Position;

use App\Models\Position;

class UpdatePosition
{
    public function __invoke(Position $position, array $data): Position
    {
        $position->update($data);

        return $position->fresh();
    }
}
