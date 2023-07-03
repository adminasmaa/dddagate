<?php

namespace App\Http\Resources\Api_Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DelegateResource extends JsonResource
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
            'name' => $this->name,
            'zone_name_ar' => $this->zone->name_ar ?? null,
            'zone_name_en' => $this->zone->name_en ?? null,
            'email' => $this->email,
            'code' => $this->code,
            'phone' => $this->phone,
            'car_type' => $this->car_type,
            'status' => $this->status,
            'image_profile' =>  $this->image_profile == 'avatar.png' ? asset('default/avatar.png') : Storage::disk('public')->url('delegates/profiles/'.$this->image_profile),
            'image_idt_front' => Storage::disk('public')->url('delegates/identifiers/'.$this->image_idt_front),
            'image_idt_back' => Storage::disk('public')->url('delegates/identifiers/'.$this->image_idt_back),
            'created_at' =>  Carbon::parse($this->created_at)->format('Y-m-d H:i'),
        ];
    }
}
