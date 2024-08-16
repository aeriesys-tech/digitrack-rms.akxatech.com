<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EquipmentType;
use App\Http\Resources\EquipmentTypeResource;

class EquipmentTypeController extends Controller
{
    public function paginateEquipmentTypes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = EquipmentType::query();

        if(isset($request->equipment_type_code))
        {
            $query->where('equipment_type_code',$request->equipment_type_code);
        }
        if(isset($request->equipment_type_name))
        {
            $query->where('equipment_type_name',$request->equipment_type_name);
        }
        
        if($request->search!='')
        {
            $query->where('equipment_type_code', 'like', "%$request->search%")
                ->orWhere('equipment_type_name', 'like', "$request->search%");
        }
        $equipmentType = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return EquipmentTypeResource::collection($equipmentType);
    }

    public function getEquipmentTypes()
    {
        $equipmentType = EquipmentType::all();
        return EquipmentTypeResource::collection($equipmentType);
    }

    public function addEquipmentType(Request $request)
    {
        $data = $request->validate([
            'equipment_type_code' => 'required|string|unique:equipment_types,equipment_type_code',
            'equipment_type_name' => 'required|string|unique:equipment_types,equipment_type_name'
        ]);
        
        $equipmentType = EquipmentType::create($data);
        return response()->json(["message" => "EquipmentType Created Successfully"]);  
    }  

    public function getEquipmentType(Request $request)
    {
        $request->validate([
            'equipment_type_id' => 'required|exists:equipment_types,equipment_type_id'
        ]);

        $equipmentType = EquipmentType::where('equipment_type_id',$request->equipment_type_id)->first();
        return new EquipmentTypeResource($equipmentType);
    }

    public function updateEquipmentType(Request $request)
    {
        $data = $request->validate([
            'equipment_type_id' => 'required|exists:equipment_types,equipment_type_id',
            'equipment_type_code' => 'required|string|unique:equipment_types,equipment_type_code,'.$request->equipment_type_id.',equipment_type_id',
            'equipment_type_name' => 'required|string|unique:equipment_types,equipment_type_name,'.$request->equipment_type_id.',equipment_type_id'
        ]);

        $equipmentType = EquipmentType::where('equipment_type_id', $request->equipment_type_id)->first();
        $equipmentType->update($data);
        return response()->json(["message" => "EquipmentType Updated Successfully"]);  
    }

    public function deleteEquipmentType(Request $request)
    {
        $request->validate([
            'equipment_type_id' => 'required|exists:equipment_types,equipment_type_id'
        ]);
        $equipmentType = EquipmentType::withTrashed()->where('equipment_type_id', $request->equipment_type_id)->first();

        if($equipmentType->trashed())
        {
            $equipmentType->restore();
            return response()->json([
                "message" =>"EquipmentType Activated successfully"
            ],200);
        }
        else
        {
            $equipmentType->delete();
            return response()->json([
                "message" =>"EquipmentType Deactivated successfully"
            ], 200); 
        }
    }
}
