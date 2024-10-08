<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetVariableResource;
use App\Models\AssetVariable;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VariableResource;
use App\Models\Variable;
use App\Models\AssetZone;
use App\Models\Asset;
use App\Models\VariableAttributeValue;
use App\Http\Resources\VariableAttributeValueResource;
use App\Models\AssetVariableValue;

class AssetVariableController extends Controller
{
    public function paginateAssetVariables(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
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

        //DropDown Variables
        $variable = Variable::whereHas('VariableAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();

        return response()->json([
            'paginate_variables' => AssetVariableResource::collection($asset_variable),
            'meta' => [
                'current_page' => $asset_variable->currentPage(),
                'last_page' => $asset_variable->lastPage(),
                'per_page' => $asset_variable->perPage(),
                'total' => $asset_variable->total(),
            ],
            'variables' => VariableResource::collection($variable)
        ]);
    }

    public function getAssetVariables()
    {
        $asset_variable = AssetVariable::all();
        return AssetVariableResource::collection($asset_variable);
    }

    public function addAssetVariable(Request $request)
    {
        // $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $data = $request->validate([
            // 'variable_id' => [
            //     'required',
            //     'exists:variables,variable_id',
            //     function ($attribute, $value, $fail) use ($request, $assetHasZones) {
            //         $exists = AssetVariable::where('variable_id', $value)
            //             ->where('asset_id', $request->asset_id)
            //             ->where(function ($query) use ($request, $assetHasZones) {
            //                 if ($assetHasZones && $request->filled('variable_asset_zones')) {
            //                     $query->whereIn('asset_zone_id', $request->variable_asset_zones);
            //                 } else {
            //                     $query->whereNull('asset_zone_id');
            //                 }
            //             })->exists();

            //         if ($exists) {
            //             if ($request->filled('variable_asset_zones') && $assetHasZones) {
            //                 $fail('The combination of Variable and Asset Zone already exists.');
            //             } else {
            //                 $fail('The combination of Variable and Asset already exists.');
            //             }
            //         }
            //     },
            // ],
            'variable_id' => 'required|exists:variables,variable_id',
            'asset_id' => 'required|exists:assets,asset_id',
            // 'variable_asset_zones' => [
            //     $assetHasZones ? 'required' : 'nullable', 
            //     'array',
            // ],
            // 'asset_zones.*' => 'nullable|exists:asset_zones,asset_zone_id'
        ]);

        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable_type = $variable->variable_type_id;
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['variable_type_id'] = $variable_type;

        $createdVariables = [];

        // if (!empty($data['variable_asset_zones'])) 
        // {
        //     foreach ($data['variable_asset_zones'] as $zoneId) 
        //     {              
        //         if (is_null($zoneId) || $zoneId == 0) 
        //         {
        //             continue;
        //         }

        //         $variableData = $data;
        //         $variableData['asset_zone_id'] = $zoneId;

        //         $assetVariable = AssetVariable::create($variableData);
        //         $createdVariables[] = new AssetVariableResource($assetVariable);

        //         foreach($request->asset_variable_attributes as $attribute)
        //         {
        //             AssetVariableValue::create([
        //                 'asset_variable_id' => $assetVariable->asset_variable_id,
        //                 'asset_id' => $assetVariable->asset_id,
        //                 'variable_id' => $variable->variable_id,
        //                 'asset_zone_id' => $assetVariable->asset_zone_id,
        //                 'variable_attribute_id' => $attribute['variable_attribute_id'],
        //                 'field_value' => $attribute['field_value'] ?? ''
        //             ]);
        //         }
        //     }
        // } 

        $assetVariable = AssetVariable::create($data);

        foreach($request->asset_variable_attributes as $attribute)
        {
            AssetVariableValue::create([
                'asset_variable_id' => $assetVariable->asset_variable_id,
                'asset_id' => $assetVariable->asset_id,
                'variable_id' => $variable->variable_id,
                // 'asset_zone_id' => $assetVariable->asset_zone_id,
                'variable_attribute_id' => $attribute['variable_attribute_id'],
                'field_value' => $attribute['field_value'] ?? ''
            ]);
        }

        return response()->json([
            new AssetVariableResource($assetVariable), 
            "message" => "AssetVariable Created Successfully"
        ]);
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
        $asset_variables = AssetVariable::where('asset_variable_id', $request->asset_variable_id)->first();
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $data = $request->validate([
            'asset_variable_id' => 'required|exists:asset_variables,asset_variable_id',
            // 'variable_id' => [
            //     'required',
            //     'exists:variables,variable_id',
            //     function ($attribute, $value, $fail) use ($request, $asset_variables) 
            //     {
            //         if ($value != $asset_variables->variable_id) {
            //             $exists = AssetVariable::where('variable_id', $value)
            //                 ->where('asset_id', $request->asset_id)
            //                 ->where(function ($query) use ($request) {
            //                     if ($request->filled('asset_zone_id')) {
            //                         $query->where('asset_zone_id', $request->asset_zone_id);
            //                     } else {
            //                         $query->whereNull('asset_zone_id');
            //                     }
            //                 })->where('asset_variable_id', '!=', $request->asset_variable_id)->exists();
    
            //             if ($exists) {
            //                 $fail('The combination of Service, Asset, and Asset Zone already exists.');
            //             }
            //         }
            //     },
            // ],
            'variable_id' => 'required|exists:variables,variable_id',
            'asset_id' => 'required|exists:assets,asset_id',
            // 'asset_zone_id' => [
            //     $assetHasZones ? 'required' : 'nullable',
            // ],
        ]);

        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable_type = $variable->variable_type_id;
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['variable_type_id'] = $variable_type;

        $asset_variable = AssetVariable::where('asset_variable_id', $request->asset_variable_id)->first();
        $asset_variable->update($data);

        if(isset($request->deleted_asset_variable_values)>0)
        {
            AssetVariableValue::whereIn('asset_variable_value_id', $request->deleted_asset_variable_values)->forceDelete();
        }

        foreach ($request->asset_variable_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? '';

            if ($fieldValue !== null) {
                AssetVariableValue::updateOrCreate(
                    [
                        'asset_variable_id' => $asset_variable->asset_variable_id,
                        // 'asset_zone_id' => $asset_variable->asset_zone_id,
                        'variable_id' => $variable->variable_id,
                        'asset_id' =>  $asset_variable->asset_id,
                        'variable_attribute_id' => $attribute['variable_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
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
            // 'asset_zone_id' => 'required|exists:asset_zones,asset_zone_id'
        ]);

        // $asset = Asset::where('asset_id', $request->asset_id)->first();
        // $asset_zone_ids = $asset->Zones->pluck('asset_zone_id')->toArray();
        $query = AssetVariable::where('asset_id', $request->asset_id);

        $asset_variable_ids =  $query->pluck('variable_id')->toArray();
        $asset_variable = Variable::whereIn('variable_id', $asset_variable_ids)->get();

        return $asset_variable;
    }

    // public function getAssetRegisterVariables(Request $request)
    // {
    //     $request->validate([
    //         'asset_id' => 'required|exists:assets,asset_id',
    //     ]);
    
    //     // Fetch the asset and its associated zones
    //     $asset = Asset::where('asset_id', $request->asset_id)->with('Zones')->first();
        
    //     if (!$asset || $asset->Zones->isEmpty()) {
    //         return response()->json(['message' => 'No asset zones found for this asset.'], 200);
    //     }
    
    //     $asset_zone_ids = $asset->Zones->pluck('asset_zone_id')->toArray();
       
    //     $variable_ids = collect();
    //     foreach ($asset_zone_ids as $zone_id) 
    //     {            
    //         $variable_collection = collect();
    //         $asset_variables = AssetVariable::where('asset_zone_id', $zone_id)->pluck('variable_id')->toArray();

    //         foreach($asset_variables as $variable_id)
    //         {
    //             $variables = Variable::where('variable_id', $variable_id)->get();
    //             $variable_collection = $variable_collection->merge($variables);
    //         }
    //         $variable_ids = $variable_ids->push($variable_collection);
    //     }
    //     return response()->json($variable_ids);
    // }    

    public function assetVariableAttributeValues(Request $request)
    {
        $request->validate([
            'variable_id' => 'required|exists:variables,variable_id'
        ]);

        $variable_attribute_values = VariableAttributeValue::where('variable_id', $request->variable_id)->get();
        return VariableAttributeValueResource::collection($variable_attribute_values);
    }
}
