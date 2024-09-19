<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spare;
use App\Models\SpareAssetType;
use App\Http\Resources\SpareResource;
use Illuminate\Support\Facades\Auth;
use App\Models\SpareAttribute;
use App\Models\SpareAttributeValue;

class SpareController extends Controller
{
    public function paginateSpares(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Spare::query();

        if(isset($request->spare_code))
        {
            $query->where('spare_code',$request->spare_code);
        }
        if(isset($request->spare_name))
        {
            $query->where('spare_name',$request->spare_name);
        }
              
        if($request->search!='')
        {
            $query->where('spare_code', 'like', "%$request->search%")
                ->orWhere('spare_name', 'like', "$request->search%");
        }
        $spare = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return SpareResource::collection($spare);
    }

    public function getSpares()
    {
        $spare = Spare::all();
        return SpareResource::collection($spare);
    }

    public function addSpare(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'spare_code' => 'required|string|unique:spares,spare_code',
            'spare_name' => 'required|string|unique:spares,spare_name',
            'spare_type_id' => 'required|exists:spare_types,spare_type_id',
            'spare_attributes' => 'nullable|array',
            'spare_attributes.*.spare_attribute_id' => 'nullable|exists:spare_attributes,spare_attribute_id',
            'spare_attributes.*.spare_attribute_value.field_value' => 'nullable',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);
        $data['plant_id'] = $userPlantId;
        
        $spare = Spare::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            SpareAssetType::create([
                'spare_id' => $spare->spare_id,
                'asset_type_id' => $asset_type,
            ]);
        }

        foreach ($request->spare_attributes as $attribute) 
        {
            SpareAttributeValue::create([
                'spare_id' => $spare->spare_id,
                'spare_attribute_id' => $attribute['spare_attribute_id'],
                'field_value' => $attribute['spare_attribute_value']['field_value'] ?? '',
            ]);
        }            
        return response()->json(["message" => "Spare Created Successfully"]);
    }

    public function getSpare(Request $request)
    {
        $request->validate([
            'spare_id' => 'required|exists:spares,spare_id'
        ]);

        $spare = Spare::where('spare_id',$request->spare_id)->first();
        return new SpareResource($spare);
    }

    public function getSpareData(Request $request)
    {
        $request->validate([
            'spare_id' => 'required|exists:spares,spare_id'
        ]);

        $spare = Spare::where('spare_id',$request->spare_id)->first();
        return new SpareResource($spare);
    }

    public function getAssetTypeSpares(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $spares = Spare::whereHas('SpareAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return SpareResource::collection($spares);
    }

    public function updateSpare(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'spare_id' => 'required|exists:spares,spare_id',
            'spare_code' => 'required|string|unique:spares,spare_code,' . $request->spare_id . ',spare_id',
            'spare_name' => 'required|string|unique:spares,spare_name,' . $request->spare_id . ',spare_id',
            'spare_type_id' => 'required|exists:spare_types,spare_type_id',
            'spare_attributes' => 'nullable|array',
            'spare_attributes.*.spare_attribute_id' => 'nullable|exists:spare_attributes,spare_attribute_id',
            'spare_attributes.*.spare_attribute_value.field_value' => 'nullable',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'deleted_spare_attribute_values' => 'nullable'
        ]);
    
        $data['plant_id'] = $userPlantId;
    
        $spare = Spare::where('spare_id', $request->spare_id)->first();
        $spare->update($data);

        SpareAssetType::where('spare_id', $spare->spare_id)->delete();

        foreach ($data['asset_types'] as $asset_type) {
            SpareAssetType::create([
                'spare_id' => $spare->spare_id,
                'asset_type_id' => $asset_type,
            ]);
        }

        if($request->deleted_spare_attribute_values > 0)
        {
            SpareAttributeValue::whereIn('spare_attribute_value_id', $request->deleted_spare_attribute_values)->forceDelete();
        }
    
        if (!empty($request->spare_attributes)) 
        {
            foreach ($request->spare_attributes as $attribute) 
            {
                $fieldValue = $attribute['spare_attribute_value']['field_value'];
    
                if ($fieldValue !== null) {
                    SpareAttributeValue::updateOrCreate(
                        [
                            'spare_id' => $spare->spare_id,
                            'spare_attribute_id' => $attribute['spare_attribute_id'],
                        ],
                        [
                            'field_value' => $fieldValue,
                        ]
                    );
                }
            }
        }    
        return response()->json(["message" => "Spare Updated Successfully"]);
    }    

    public function deleteSpare(Request $request)
    {
        $request->validate([
            'spare_id' => 'required|exists:spares,spare_id'
        ]);
        $spare = Spare::withTrashed()->where('spare_id', $request->spare_id)->first();

        if($spare->trashed())
        {
            $spare->restore();
            return response()->json([
                "message" =>"Spare Activated successfully"
            ],200);
        }
        else
        {
            $spare->delete();
            return response()->json([
                "message" =>"Spare Deactivated successfully"
            ], 200);
        }
    }
}
