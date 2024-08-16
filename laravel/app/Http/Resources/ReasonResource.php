<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReasonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return[
            'reason_id' => $this->reason_id,
            'reason_code' => $this->reason_code,
            'reason_name'=>$this->reason_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
