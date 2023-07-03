<?php

namespace App\Http\Resources\Api_Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ShopResource extends JsonResource
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
            'fullname' => $this->fullname,
            'zone_name_ar' => $this->zone->name_ar,
            'zone_name_en' => $this->zone->name_en,
            'phone' => $this->phone,
            'status' => $this->status,
            'status_requested' => $this->status_requested,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'address' => $this->address,
            'is_assigned' => $this->user_id ? true : false,
            'image_profile' =>  $this->image_profile == 'avatar.png' ? asset('default/avatar.png') : Storage::disk('public')->url('shops/profiles/'.$this->image_profile),
            'image_idt_front' => Storage::disk('public')->url('shops/identifiers/'.$this->image_idt_front),
            'image_idt_back' => Storage::disk('public')->url('shops/identifiers/'.$this->image_idt_back),
            'created_at' =>  Carbon::parse($this->created_at)->format('Y-m-d H:i'),
            'delegate_assigned' => new DelegateResource($this->whenLoaded('delegate')),
        ];
    }
}
