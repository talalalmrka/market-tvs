<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,

            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,

            'duration' => $this->duration,
            'is_active' => (bool) $this->is_active,

            'slides' => SlideResource::collection(
                $this->slides
                    ->where('is_active', true)
                    ->sortBy('order')
                    ->values()
            ),
        ];
    }
}
