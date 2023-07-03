<?php

namespace App\Http\Resources\Api_Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i'),
            'zones'  => ZoneResource::collection($this->whenLoaded('zones')),
        ];
    }
}
