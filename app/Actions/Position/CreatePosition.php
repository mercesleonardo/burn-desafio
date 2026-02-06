<?php

namespace App\Actions\Position;

use App\Models\Position;

class CreatePosition
{
    public function __invoke(array $data): Position
    {
        return Position::create($data);
    }
}
