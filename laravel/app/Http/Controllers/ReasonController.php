<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reason;
use App\Http\Resources\ReasonResource;

class ReasonController extends Controller
{
    public function paginateReasons(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Reason::query();

        if(isset($request->reason_code))
        {
            $query->where('reason_code',$request->reason_code);
        }
        if(isset($request->reason_name))
        {
            $query->where('reason_name',$request->reason_name);
        }
        
        if($request->search!='')
        {
            $query->where('reason_code', 'like', "%$request->search%")
                ->orWhere('reason_name', 'like', "%$request->search%");
        }
        $reason = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return ReasonResource::collection($reason);
    }

    public function addReason(Request $request)
    {
        $data = $request->validate([
            'reason_code' => 'required|unique:reasons,reason_code',
            'reason_name' => 'required|unique:reasons,reason_name'
        ], [
            'reason_name.required' => 'activity type code is required.',
            'reason_code.required' => 'activity type name is required.',
            'reason_name.unique' => 'activity type code has already been taken.',
            'reason_code.unique' => 'activity type name has already been taken.'
        ]);

        $reason = Reason::create($data);
        return response()->json(["message" => "Activity Type Created Successfully"]); 
    }

    public function getReason(Request $request)
    {
        $request->validate([
            'reason_id' => 'required|exists:reasons,reason_id'
        ]);

        $reason = Reason::where('reason_id', $request->reason_id)->first();
        return new ReasonResource($reason);
    }

    public function getReasons()
    {
        $reason = Reason::all();
        return ReasonResource::collection($reason);
    }

    public function updateReason(Request $request)
    {
        $data = $request->validate([
            'reason_id' => 'required|exists:reasons,reason_id',
            'reason_code' => 'required|unique:reasons,reason_code,'.$request->reason_id.',reason_id',
            'reason_name' => 'required|unique:reasons,reason_name,'.$request->reason_id.',reason_id'
        ],[
            'reason_name.required' => 'activity type name is required.',
            'reason_name.unique' => 'activity type name has already been taken.',
            'reason_code.required' => 'activity type code is required.',
            'reason_code.unique' => 'activity type code has already been taken.'
        ]);

        $reason = Reason::where('reason_id', $request->reason_id)->first();
        $reason->update($data);
        return response()->json(["message" => "Activity Type Updated Successfully"]); 
    }

    public function deleteReason(Request $request)
    {
        $request->validate([
            'reason_id' => 'required|exists:reasons,reason_id'
        ]);
        $reason = Reason::withTrashed()->where('reason_id', $request->reason_id)->first();

        if($reason->trashed())
        {
            $reason->restore();
            return response()->json([
                "message" => "Activity Type Activated Successfully"
            ],200);
        }
        else
        {
            $reason->delete();
            return response()->json([
                "message" => "Activity Type Deactivated Successfully"
            ], 200);
        }
    }
}
