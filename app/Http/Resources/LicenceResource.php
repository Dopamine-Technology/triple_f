<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'issued_by'=>$this->issued_by,
            'issued_date'=>$this->issued_date->format('Y-m-d'),
            'expiration_date'=>$this->expiration_date->format('Y-m-d'),
            'licence_id'=>$this->licence_id,
            'created_at'=>$this->created_at->format('Y-m-d h:i'),
        ];
    }
}
