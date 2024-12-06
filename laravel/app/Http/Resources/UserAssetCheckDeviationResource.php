<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserCheck;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Check;
use App\Models\Department;

class UserAssetCheckDeviationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user_check = UserCheck::where('user_check_id', $this->user_check_id)->first();
        $asset = Asset::where('asset_id', $user_check->asset_id)->first();

        $asset_type = AssetType::where('asset_type_id', $asset->asset_type_id)->first();
        $check = Check::where('check_id', $this->check_id)->first();
        $department = Department::where('department_id',  $user_check->department_id)->first();

        return [
            'user_asset_check_id' => $this->user_asset_check_id,
            'check_id' => $this->check_id,
            'check' => $check,
            'user_check_id' => $this->user_check_id,
            'user_check' => $user_check,
            'asset' => new AssetResource($asset),
            'asset_type' => $asset_type,
            'asset_check_id' => $this->asset_check_id,
            'field_name' => $this->field_name,
            'field_type' => $this->field_type,
            'default_value' => $this->default_value,
            'is_required' => $this->is_required,
            'lcl' => $this->lcl,
            'ucl' => $this->ucl,
            'field_values' => $this->field_values,
            'order' => $this->order,
            'value' => $this->value,
            'department' => $department,
            'remark_user_id' => $this->remark_user_id,
            'remark_user' => $this->RemarkUser,
            'remark_status' => $this->remark_status?true:false,
            'remarks' => $this->remarks 
        ];
    }
}
