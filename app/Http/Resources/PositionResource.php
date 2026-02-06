<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'company'     => $this->whenLoaded('company'),
            'title'       => $this->title,
            'description' => $this->description,
            'type'        => $this->type,
            'salary'      => $this->salary,
            'schedule'    => $this->schedule,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
