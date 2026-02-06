<?php

namespace App\Actions\Position;

use App\Models\Position;

class DeletePosition
{
    public function __invoke(Position $position): void
    {
        $position->delete();
    }
}
