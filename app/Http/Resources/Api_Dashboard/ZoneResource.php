<?php

namespace App\Http\Resources\Api_Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZoneResource extends JsonResource
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
            'state_name_ar' => $this->state->name_ar,
            'state_name_en' => $this->state->name_en,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i'),
            'delegates' => DelegateResource::collection($this->whenLoaded('delegates')),
            'shops' => ShopResource::collection($this->whenLoaded('shops')),
        ];
    }
}
