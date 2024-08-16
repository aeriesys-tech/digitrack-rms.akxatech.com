<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClusterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return[
            'cluster_id' => $this->cluster_id,
            'cluster_code' => $this->cluster_code,
            'cluster_name' => $this->cluster_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
