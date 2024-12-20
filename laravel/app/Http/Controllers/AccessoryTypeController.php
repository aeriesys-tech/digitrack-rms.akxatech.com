<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessoryType;
use App\Http\Resources\AccessoryTypeResource;

class AccessoryTypeController extends Controller
{
    public function paginateAccessoryTypes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AccessoryType::query();

        if(isset($request->accessory_type_code))
        {
            $query->where('accessory_type_code',$request->accessory_type_code);
        }

        if(isset($request->accessory_type_name))
        {
            $query->where('accessory_type_name',$request->accessory_type_name);
        }
    
        if($request->search!='')
        {
            $query->where('accessory_type_code', 'like', "%$request->search%")
                 ->orWhere('accessory_type_name', 'like', "%$request->search%");
        }

        $accessory_type = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AccessoryTypeResource::collection($accessory_type);
    }

    public function getAccessoryTypes()
    {
        $accessory_type = AccessoryType::all();
        return AccessoryTypeResource::collection($accessory_type);
    }

    public function addAccessoryType(Request $request)
    {
        $data = $request->validate([
            'accessory_type_code' => 'required|string|unique:accessory_types,accessory_type_code',
            'accessory_type_name' => 'required|string|unique:accessory_types,accessory_type_name'
        ]);

        $accessory_type = AccessoryType::create($data);
        return response()->json(["message" => "Accessory Type Created Successfully"]);
    }

    public function getAccessoryType(Request $request)
    {
        $request->validate([
            'accessory_type_id' => 'required|exists:accessory_types,accessory_type_id'
        ]);

        $accessory_type = AccessoryType::where('accessory_type_id',$request->accessory_type_id)->first();
        return new AccessoryTypeResource($accessory_type);
    }

    public function updateAccessoryType(Request $request)
    {
        $data = $request->validate([
            'accessory_type_id' => 'required|exists:accessory_types,accessory_type_id',
            'accessory_type_code' => 'required|unique:accessory_types,accessory_type_code,'.$request->accessory_type_id.',accessory_type_id',
            'accessory_type_name' => 'required|unique:accessory_types,accessory_type_name,'.$request->accessory_type_id.',accessory_type_id'
        ]);

        $accessory_type = AccessoryType::where('accessory_type_id', $request->accessory_type_id)->first();
        $accessory_type->update($data);
        return response()->json(["message" => "Accessory Type Updated Successfully"]);  
    }

    public function deleteAccessoryType(Request $request)
    {
        $request->validate([
            'accessory_type_id' => 'required|exists:accessory_types,accessory_type_id'
        ]);

        $accessory_type = AccessoryType::withTrashed()->where('accessory_type_id',$request->accessory_type_id)->first();

        if($accessory_type->trashed())
        {
            $accessory_type->restore();
            return response()->json([
                "message" =>"Accessory Type Activated Successfully"
            ],200);
        }
        else
        {
            $accessory_type->delete();
            return response()->json([
                "message" =>"Accessory Type Deactivated Successfully"
            ], 200); 
        }
    }
}
