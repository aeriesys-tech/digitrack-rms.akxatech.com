<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrequencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'frequency_id' => $this->frequency_id,
            'frequency_code' => $this->frequency_code,
            'frequency_name' => $this->frequency_code,
            'status' => $this->deleted_at?false:true
        ];
    }
}
