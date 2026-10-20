<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'property_type' => $this->property_type,
            'rental_price' => $this->rental_price,
            'currency' => $this->currency,
            'city' => $this->city,
            'status' => $this->status,
            'availability_status' =>$this->availability_status,
        ];
    }
}
