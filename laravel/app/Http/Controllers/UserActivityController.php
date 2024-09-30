<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivity;
use App\Http\Resources\UserActivityResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ActivityAttributeResource;
use App\Models\ActivityAttribute;
use App\Models\ActivityAttributeValue;

class UserActivityController extends Controller
{
    public function paginateUserActivities(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        // $authPlantId = Auth::User()->plant_id;
        $query =  UserActivity::query();

        // $query->where('plant_id', $authPlantId);

        if(isset($request->activity_no))
        {
            $query->where('activity_no',$request->activity_no);
        }
        if(isset($request->activity_date))
        {
            $query->where('activity_date',$request->activity_date);
        }
        if(isset($request->user_id))
        {
            $query->where('user_id',$request->user_id);
        }
        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }
        if(isset($request->status))
        {
            $query->where('status',$request->status);
        }
        
        if($request->search!='')
        {
            $query->where('activity_no', 'like', "%$request->search%")
                ->orWhere('activity_date', 'like', "%$request->search%")
                ->orWhere('status', 'like', "%$request->search%")
                ->orwhereHas('Reason', function($que) use($request){
                    $que->where('reason_name', 'like', "%$request->search%");
                })->orwhereHas('Asset', function($que) use($request){
                    $que->where('asset_code', 'like', "%$request->search%");
                });
        }
        if ($request->keyword == 'asset_code') {
            $query->join('assets', 'user_activities.asset_id', '=', 'assets.asset_id')->select('user_activities.*') 
                  ->orderBy('assets.asset_code', $request->order_by);
        }
        elseif ($request->keyword == 'reason_code') {
            $query->join('reasons', 'user_activities.reason_id', '=', 'reasons.reason_id')->select('user_activities.*') 
                  ->orderBy('reasons.reason_code', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }

        $activity = $query->paginate($request->per_page); 
        return UserActivityResource::collection($activity);
    }
    
    public function addUserActivity(Request $request)
    {
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $data = $request->validate([
            'activity_date' => 'required',
            'asset_id' => 'required|exists:assets,asset_id',
            'status' => 'required',
            'reason_id' => 'required|exists:reasons,reason_id',
            'activity_attributes' => 'nullable|array',
            'activity_attributes.*.activity_attribute_id' => 'nullable|exists:activity_attributes,activity_attribute_id',
            'activity_attributes.*.activity_attribute_value.field_value' => 'nullable',
            'cost' => 'nullable',
            'note' => 'nullable'
        ]);
    
        $data['activity_no'] = $this->generateActivityNo();
        $data['user_id'] = Auth::User()->user_id;
        $data['plant_id'] = $asset->plant_id;
    
        $UserActivity = UserActivity::create($data);

        foreach ($request->activity_attributes as $attribute) 
        {
            ActivityAttributeValue::create([
                'user_activity_id' => $UserActivity->user_activity_id,
                'activity_attribute_id' => $attribute['activity_attribute_id'],
                'field_value' => $attribute['activity_attribute_value']['field_value'] ?? '',
            ]);
        }        
        return response()->json(["message" => "Activity Register Created Successfully"]);
    }    

    public function getUserActivity(Request $request)
    {
        $request->validate([
            'user_activity_id' => 'required|exists:user_activities,user_activity_id'
        ]);

        $activity = UserActivity::where('user_activity_id', $request->user_activity_id)->first();
        return new UserActivityResource($activity);
    }

    public function updateUserActivity(Request $request)
    {
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $data = $request->validate([
            'user_activity_id' => 'required|exists:user_activities,user_activity_id',
            'activity_date' => 'required',
            'asset_id' => 'required|exists:assets,asset_id',
            'status' => 'required',
            'reason_id' => 'required|exists:reasons,reason_id',
            'cost' => 'nullable',
            'note' => 'nullable',
            'activity_attributes' => 'nullable|array',
            'activity_attributes.*.activity_attribute_id' => 'nullable|exists:activity_attributes,activity_attribute_id',
            'activity_attributes.*.activity_attribute_value.field_value' => 'nullable',
        ]);
        $data['user_id'] = Auth::User()->user_id;
        $data['plant_id'] = $asset->plant_id;

        $UserActivity = UserActivity::where('user_activity_id', $request->user_activity_id)->first();
        $UserActivity->update($data);

        if($request->deleted_activity_attribute_values > 0)
        {
            ActivityAttributeValue::whereIn('activity_attribute_value_id', $request->deleted_activity_attribute_values)->forceDelete();
        }
    
        if (!empty($request->activity_attributes)) 
        {
            foreach ($request->activity_attributes as $attribute) 
            {
                $fieldValue = $attribute['activity_attribute_value']['field_value'];
    
                if ($fieldValue !== null) {
                    ActivityAttributeValue::updateOrCreate(
                        [
                            'user_activity_id' => $UserActivity->user_activity_id,
                            'activity_attribute_id' => $attribute['activity_attribute_id'],
                        ],
                        [
                            'field_value' => $fieldValue,
                        ]
                    );
                }
            }
        }    
        return response()->json(["message" => "Activity Register Updated Successfully"]);
    }

    public function deleteUserActivity(Request $request)
    {
        $request->validate([
            'user_activity_id' => 'required|exists:user_activities,user_activity_id',
        ]);

        ActivityAttributeValue::where('user_activity_id', $request->user_activity_id)->forceDelete();
        $activity = UserActivity::where('user_activity_id', $request->user_activity_id)->forceDelete();
        return response()->json([
            "message" => "UserActivity Deleted Successfully"
        ]);
    }

    public function generateActivityNo()
    {
        $lastActivity = UserActivity::latest()->first();
        $nextActivityNumber = 1; 
        
        if ($lastActivity) {
            $lastActivityNumber = (int) substr($lastActivity->activity_no, 9); 
            $nextActivityNumber = $lastActivityNumber + 1;
        }
        
        $formattedNextActivityNumber = str_pad($nextActivityNumber, 4, '0', STR_PAD_LEFT);
        $activity_no = 'Activity_' . $formattedNextActivityNumber;
        
        while (UserActivity::where('activity_no', $activity_no)->exists()) {
            $nextActivityNumber++;
            $formattedNextActivityNumber = str_pad($nextActivityNumber, 4, '0', STR_PAD_LEFT);
            $activity_no = 'Activity_' . $formattedNextActivityNumber;
        }
        return $activity_no;
    }

    public function getActivitiesDropdown(Request $request)
    {
        $request->validate([
            'reason_id' => 'required|exists:reasons,reason_id'
        ]);

        $activity = ActivityAttribute::whereHas('ActivityAttributeTypes', function($que) use($request){
            $que->where('reason_id', $request->reason_id);
        })->get();

        return ActivityAttributeResource::collection($activity);
    }
}
