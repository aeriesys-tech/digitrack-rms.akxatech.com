<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetVariableResource;
use App\Models\AssetVariable;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VariableResource;
use App\Models\Variable;

class AssetVariableController extends Controller
{
    public function paginateAssetVariables(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AssetVariable::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->variable_id))
        {
            $query->where('variable_id',$request->variable_id);
        }
        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orWherehas('Asset', function($query) use($request){
                $query->where('asset_name', 'like', "$request->search%");
            })->orWherehas('Variable', function($query) use($request){
                $query->where('variable_name', 'like', "$request->search%");
            });
        }
        $asset_variable = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return AssetVariableResource::collection($asset_variable);
    }

    public function getAssetVariables()
    {
        $asset_variable = AssetVariable::all();
        return AssetVariableResource::collection($asset_variable);
    }

    public function addAssetVariable(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $areaId = Auth::User()->Plant->area_id;
        $data = $request->validate([
            'variable_id' => [
                'required',
                'exists:variables,variable_id',
                function ($attribute, $value, $fail) use ($request) 
                {
                    $exists = AssetVariable::where('variable_id', $value)
                        ->where('asset_id', $request->asset_id)
                        ->where(function ($query) use ($request) {
                            if (!empty($request->asset_zones)) {
                                $query->whereIn('asset_zone_id', $request->asset_zones);
                            } else {
                                $query->whereNull('asset_zone_id');
                            }
                        })
                        ->exists();
    
                    if ($exists) {
                        $fail('The combination of Variable and Asset Zone already exists.');
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zones' => 'nullable|array', 
            'asset_zones.*' => 'nullable|exists:asset_zones,asset_zone_id'
        ]);

        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable_type = $variable->variable_type_id;

        $data['plant_id'] = $userPlantId;
        $data['area_id'] = $areaId;
        $data['variable_type_id'] = $variable_type;

        $createdVariables = [];

        if (!empty($data['asset_zones'])) 
        {
            foreach ($data['asset_zones'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $variableData = $data;
                $variableData['asset_zone_id'] = $zoneId;

                $assetVariable = AssetVariable::create($variableData);
                $createdVariables[] = new AssetVariableResource($assetVariable);
            }
        } 
        else 
        {
            $variableData = $data;
            $variableData['asset_zone_id'] = null;

            $assetVariable = AssetVariable::create($variableData);
            $createdVariables[] = new AssetVariableResource($assetVariable);
        }

        return response()->json([$createdVariables, "message" => "AssetVariable Created Successfully"]);
    }

    public function getAssetVariable(Request $request)
    {
        $request->validate([
            'asset_variable_id' => 'required|exists:asset_variables,asset_variable_id'
        ]);

        $asset_variable = AssetVariable::where('asset_variable_id',$request->asset_variable_id)->first();
        return new AssetVariableResource($asset_variable);
    }

    public function updateAssetVariable(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $areaId = Auth::User()->Plant->area_id;
        $asset_variable = AssetVariable::where('asset_variable_id', $request->asset_variable_id)->first();

        $data = $request->validate([
            'asset_variable_id' => 'required|exists:asset_variables,asset_variable_id',
            'variable_id' => [
            'required',
            'exists:variables,variable_id',
            function ($attribute, $value, $fail) use ($request, $asset_variable) {
                $exists = AssetVariable::where('variable_id', $value)
                    ->where('asset_id', $request->asset_id)
                    ->where(function ($query) use ($request) {
                        if ($request->filled('asset_zone_id')) {
                            $query->where('asset_zone_id', $request->asset_zone_id);
                        } else {
                            $query->whereNull('asset_zone_id');
                        }
                    })
                    ->where('asset_variable_id', '!=', $request->asset_variable_id)->exists();

                if ($exists) {
                    $fail('The combination of Variable and Asset Zone already exists.');
                }
            },
        ],
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zones' => 'nullable|array',
            'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
        ]);

        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable_type = $variable->variable_type_id;

        $data['plant_id'] = $userPlantId;
        $data['area_id'] = $areaId;
        $data['variable_type_id'] = $variable_type;

        $asset_variable = AssetVariable::where('asset_variable_id', $request->asset_variable_id)->first();
        $asset_variable->update($data);
        return response()->json([
            "message" => "AssetVariable Updated Successfully",
            new AssetVariableResource($asset_variable)
        ]); 
    }

    public function deleteAssetVariable(Request $request)
    {
        $request->validate([
            'asset_variable_id' => 'required|exists:asset_variables,asset_variable_id'
        ]);

        AssetVariable::where('asset_variable_id', $request->asset_variable_id)->delete();

        return response()->json([
            'message' => "AssetVariable Deleted Successfully"
        ]);
    }

    public function getAssetTypeVariables(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $variable = Variable::whereHas('VariableAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return VariableResource::collection($variable);
    }


    public function getAssetRegisterVariables(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id'
        ]);
        $query = AssetVariable::query();

        if (isset($request->asset_zone_id)) 
        {
            $query->where('asset_zone_id', $request->asset_zone_id);
        }

        $asset_variable = $query->where('asset_id', $request->asset_id)->get();
        return AssetVariableResource::collection($asset_variable);
    }
}
