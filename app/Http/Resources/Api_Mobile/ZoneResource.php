<?php

namespace App\Http\Resources\Api_Mobile;

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
        $lang = $request->header('locatization');

        $name = 'name_'.$lang;

        return [
            'id' => $this->id,
            'name' => $this->$name,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i'),

        ];
    }
}
