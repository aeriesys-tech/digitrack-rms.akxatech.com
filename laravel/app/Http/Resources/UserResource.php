<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'role_id' => $this->role_id,
            'role' => new RoleResource($this->Role),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'address' => $this->address,
            'avatar' => $this->avatar ? config('app.asset_url').'users/'.$this->avatar : null,
            'status' => $this->deleted_at?false:true,
            'consent' => $this->Consent,
            'department_id' => $this->department_id,
            'department' => new DepartmentResource($this->Department)
        ];
    }
}
