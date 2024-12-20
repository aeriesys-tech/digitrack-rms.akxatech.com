<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakDownType;
use App\Http\Resources\BreakDownTypeResource;

class BreakDownTypeController extends Controller
{
    public function paginateBreakDownTypes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = BreakDownType::query();

        if(isset($request->break_down_type_code))
        {
            $query->where('break_down_type_code',$request->break_down_type_code);
        }
        if(isset($request->break_down_type_name))
        {
            $query->where('break_down_type_name',$request->break_down_type_name);
        }
        
        if($request->search!='')
        {
            $query->where('break_down_type_code', 'like', "%$request->search%")
                ->orWhere('break_down_type_name', 'like', "%$request->search%");
        }
        $break_down_type = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return BreakDownTypeResource::collection($break_down_type);
    }

    public function getBreakDownTypes()
    {
        $break_down_type = BreakDownType::all();
        return BreakDownTypeResource::collection($break_down_type);
    }

    public function addBreakDownType(Request $request)
    {
        $data = $request->validate([
            'break_down_type_code' => 'required|string|unique:break_down_types,break_down_type_code',
            'break_down_type_name' => 'required|string|unique:break_down_types,break_down_type_name'
        ]);
        
        $break_down_type = BreakDownType::create($data);
        return response()->json(["message" => "BreakDown Type Created Successfully"]); 
    }
    
    public function getBreakDownType(Request $request)
    {
        $request->validate([
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id'
        ]);

        $break_down_type =BreakDownType::where('break_down_type_id',$request->break_down_type_id)->first();
        return new BreakDownTypeResource($break_down_type);
    }

    public function updateBreakDownType(Request $request)
    {
        $data = $request->validate([
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id',
            'break_down_type_code' => 'required|unique:break_down_types,break_down_type_code,'.$request->break_down_type_id.',break_down_type_id',
            'break_down_type_name' => 'required|unique:break_down_types,break_down_type_name,'.$request->break_down_type_id.',break_down_type_id'
        ]);

        $break_down_type = BreakDownType::where('break_down_type_id', $request->break_down_type_id)->first();
        $break_down_type->update($data);
        return response()->json(["message" => "BreakDown Type Updated Successfully"]);  
    }

    public function deleteBreakDownType(Request $request)
    {
        $request->validate([
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id'
        ]);

        $break_down_type = BreakDownType::withTrashed()->where('break_down_type_id',$request->break_down_type_id)->first();

        if($break_down_type->trashed())
        {
            $break_down_type->restore();
            return response()->json([
                "message" =>"BreakDown Type Activated Successfully"
            ],200);
        }
        else
        {
            $break_down_type->delete();
            return response()->json([
                "message" =>"BreakDown Type Deactivated Successfully"
            ], 200); 
        }
    }
}
