<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'section_id' => $this->section_id,
            'section_code' => $this->section_code,
            'section_name' => $this->section_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
