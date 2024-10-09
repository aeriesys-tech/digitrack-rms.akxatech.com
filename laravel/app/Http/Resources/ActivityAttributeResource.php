<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityAttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $reasons = [];
        foreach($this->ActivityAttributeTypes as $activityAttributeType)
        {
            array_push($reasons, $activityAttributeType['reason_id']);
        }
        return [
            'activity_attribute_id' => $this->activity_attribute_id,
            'field_name' => $this->field_name,
            'display_name' => $this->display_name,
            'field_type' => $this->field_type,
            'field_length' => $this->field_length,
            'field_values' => $this->field_values,
            'is_required' => $this->is_required,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->User),
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter),
            'activity_types' => $reasons,
            'activity_attribute_types' => ActivityAttributeTypeResource::collection($this->ActivityAttributeTypes),
            'activity_attribute_value' => [
                "field_value" => null
            ],
            'status' => $this->deleted_at?false:true
        ];
    }
}
