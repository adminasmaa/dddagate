<?php

namespace App\Http\Resources\Api_Mobile;

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
        $lang = $request->header('locatization');

        $name = 'name_'.$lang;
        return [
            'id' => $this->id,
            'name' => $this->$name,

            'fullname' => $this->fullname,
            'zone_id' => $this->zone_id,
            'zone_name' => $this->zone->$name,
            'phone' => $this->phone,
//            'status' => $this->status,
//            'status_requested' => $this->status_requested,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'address' => $this->address,
            'image_profile' =>  Storage::disk('public')->url('shops/profiles/'.$this->image_profile),
            'image_idt_front' => Storage::disk('public')->url('shops/identifiers/'.$this->image_idt_front),
            'image_idt_back' => Storage::disk('public')->url('shops/identifiers/'.$this->image_idt_back),
            'created_at' =>  Carbon::parse($this->created_at)->format('Y-m-d H:i'),
        ];
    }
}
