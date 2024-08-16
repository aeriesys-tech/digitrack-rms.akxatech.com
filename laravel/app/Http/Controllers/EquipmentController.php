<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Http\Resources\EquipmentResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    public function paginateEquipment(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $authPlantId = Auth::User()->plant_id;
        $query = Equipment::query();

        $query->where('plant_id', $authPlantId);

        if(isset($request->equipment_code))
        {
            $query->where('equipment_code',$request->equipment_code);
        }
        if(isset($request->equipment_name))
        {
            $query->where('equipment_name',$request->equipment_name);
        }
        
        if($request->search!='')
        {
            $query->where('equipment_code', 'like', "%$request->search%")
                ->orWhere('equipment_name', 'like', "$request->search%")
                ->orWhereHas('EquipmentType', function ($q) use ($request) {
                    $q->where('equipment_type_code', 'like', "$request->search%")
                    ->orWhere('equipment_type_name', 'like', "$request->search%");
                });
        }
        $activity = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return EquipmentResource::collection($activity);
    }

    public function getEquipments()
    {
        $userPlantId = Auth::User()->plant_id;
        $equipment = Equipment::where('plant_id', $userPlantId)->get();
    
        return EquipmentResource::collection($equipment);
    }

    public function addEquipment(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'equipment_type_id' => 'required|exists:equipment_types,equipment_type_id',
            'equipment_code' => [
                'required',
                'string',
                Rule::unique('equipment')->where(function ($query) use ($userPlantId) {
                    return $query->where('plant_id', $userPlantId);
                }),
            ],
            'equipment_name' => [
                'required',
                'string',
                Rule::unique('equipment')->where(function ($query) use ($userPlantId) {
                    return $query->where('plant_id', $userPlantId);
                }),
            ],
            'description' => 'nullable'
        ]);
        $data['plant_id'] = $userPlantId;
        
        $equipment = Equipment::create($data);
        return response()->json(["message" => "Equipment Created Successfully"]);
    }  

    public function getEquipment(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,equipment_id'
        ]);

        $equipment = Equipment::where('equipment_id',$request->equipment_id)->first();
        return new EquipmentResource($equipment);
    }

    public function updateEquipment(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'equipment_id' => 'required|exists:equipment,equipment_id',
            'equipment_type_id' => 'required|exists:equipment_types,equipment_type_id',
            'equipment_code' => [
                'required',
                'string',
                Rule::unique('equipment')->where(function ($query) use ($userPlantId, $request) {
                    return $query->where('plant_id', $userPlantId)->where('equipment_type_id', $request->equipment_type_id)->where('equipment_id', '!=', $request->equipment_id);
                }),
            ],
            'equipment_name' => [
                'required',
                'string',
                Rule::unique('equipment')->where(function ($query) use ($userPlantId, $request) {
                    return $query->where('plant_id', $userPlantId)->where('equipment_type_id', $request->equipment_type_id)->where('equipment_id', '!=', $request->equipment_id);
                }),
            ],
            'description' => 'nullable'
        ]);
        $data['plant_id'] = $userPlantId;

        $equipment = Equipment::where('equipment_id', $request->equipment_id)->first();
        $equipment->update($data);
        return response()->json(["message" => "Equipment Updated Successfully"]);
    }

    public function deleteEquipment(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,equipment_id'
        ]);
        $equipment = Equipment::withTrashed()->where('equipment_id', $request->equipment_id)->first();

        if($equipment->trashed())
        {
            $equipment->restore();
            return response()->json([
                "message" =>"Equipment Activated successfully"
            ],200);
        }
        else
        {
            $equipment->delete();
            return response()->json([
                "message" =>"Equipment Deactivated successfully"
            ], 200); 
        }
    }
}
