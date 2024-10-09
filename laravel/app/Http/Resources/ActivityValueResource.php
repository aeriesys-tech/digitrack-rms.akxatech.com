<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ActivityAttributeValue;

class ActivityValueResource extends JsonResource
{
    protected $activityId;

    public function __construct($resource)
    {
        parent::__construct($resource['resource']);
        $this->activityId = $resource['user_activity_id'] ?? null;
    }
    public function toArray(Request $request): array
    {
        $activity_attribute_value = ActivityAttributeValue::where('user_activity_id', $this->activityId)
        ->where('activity_attribute_id', $this->activity_attribute_id)->first();

    if(!$activity_attribute_value){
        $activity_attribute_value = [
            'field_value' => null
        ];
    }      

    return [
            'activity_attribute_id' => $this->activity_attribute_id,
            'field_name' => $this->field_name,
            'display_name' => $this->display_name,
            'field_type' => $this->field_type, 
            'field_values' => $this->field_values,
            'field_length' => $this->field_length,
            'is_required' => $this->is_required? 1 :0,
            'user_id' => $this->user_id,
            'status' => $this->deleted_at?false:true,
            'activity_attribute_value' => $activity_attribute_value,
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter),
        ];
    }
}
