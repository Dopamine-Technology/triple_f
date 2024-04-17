<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
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
            'credential_id'=>$this->credential_id,
        ];
    }
}
