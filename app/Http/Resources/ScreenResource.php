<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScreenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        /* return [
            'id' => $this->id,

            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,

            'is_active' => (bool) $this->is_active,

            'time_slots' => TimeSlotResource::collection(
                $this->timeSlots
                    ->where('is_active', true)
                    ->sortBy('priority')
                    ->values()
            ),
        ]; */
    }
}
