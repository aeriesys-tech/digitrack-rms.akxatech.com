<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivity;
use App\Http\Resources\UserActivityResource;
use Illuminate\Support\Facades\Auth;

class UserActivityController extends Controller
{
    public function paginateUserActivities(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $authPlantId = Auth::User()->plant_id;
        $query =  UserActivity::query();

        $query->where('plant_id', $authPlantId);

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
                ->orWhere('activity_date', 'like', "$request->search%")
                ->orWhere('status', 'like', "$request->search%");
        }
        $speed = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return UserActivityResource::collection($speed);
    }
    
    public function addUserActivity(Request $request)
    {
        $data = $request->validate([
            'activity_date' => 'required',
            'asset_id' => 'required|exists:assets,asset_id',
            'status' => 'required',
            'activity_status' => 'required|sometimes',
            'reason_id' => 'nullable|required_if:activity_status,Removed|exists:reasons,reason_id',
            'cost' => 'nullable',
            'note' => 'nullable'
        ]);
    
        $data['activity_no'] = $this->generateActivityNo();
        $data['user_id'] = Auth::User()->user_id;
        $data['plant_id'] = Auth::User()->plant_id;
    
        $UserActivity = UserActivity::create($data);
        return response()->json(["message" => "UserActivity Created Successfully"]);
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
        $data = $request->validate([
            'user_activity_id' => 'required|exists:user_activities,user_activity_id',
            'activity_date' => 'required',
            'asset_id' => 'required|exists:assets,asset_id',
            'status' => 'required',
            'activity_status' => 'required|sometimes',
            'reason_id' => 'nullable|required_if:activity_status,Removed|exists:reasons,reason_id',
            'cost' => 'nullable',
            'note' => 'nullable'
        ]);
        $data['user_id'] = Auth::User()->user_id;
        $data['plant_id'] = Auth::User()->plant_id;

        $UserActivity = UserActivity::where('user_activity_id', $request->user_activity_id)->first();
        $UserActivity->update($data);
        return response()->json(["message" => "UserActivity Updated Successfully"]);
    }

    public function deleteUserActivity(Request $request)
    {
        $request->validate([
            'user_activity_id' => 'required|exists:user_activities,user_activity_id',
        ]);

        $activity = UserActivity::where('user_activity_id', $request->user_activity_id)->delete();
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
}
