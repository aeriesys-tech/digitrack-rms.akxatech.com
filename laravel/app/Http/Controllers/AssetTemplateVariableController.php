<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AssetTemplateVariable;
use App\Models\Variable;
use App\Models\AssetTemplate;
use App\Http\Resources\AssetTemplateVariableResource;
use App\Http\Resources\VariableResource;
use App\Models\TemplateVariableValue;
use App\Models\TemplateZone;

class AssetTemplateVariableController extends Controller
{
    public function paginateAssetTemplateVariables(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
        ]);

        $query = AssetTemplateVariable::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->variable_id))
        {
            $query->where('variable_id',$request->variable_id);
        }
        if(isset($request->asset_template_id))
        {
            $query->where('asset_template_id',$request->asset_template_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orWherehas('AssetTemplate', function($query) use($request){
                $query->where('template_name', 'like', "$request->search%");
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
            'paginate_variables' => AssetTemplateVariableResource::collection($asset_variable),
            'meta' => [
                'current_page' => $asset_variable->currentPage(),
                'last_page' => $asset_variable->lastPage(),
                'per_page' => $asset_variable->perPage(),
                'total' => $asset_variable->total(),
            ],
            'variables' => VariableResource::collection($variable)
        ]);
    }

    public function addAssetTemplateVariable(Request $request)
    {
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $data = $request->validate([
            'variable_id' => [
                'required',
                'exists:variables,variable_id',
                function ($attribute, $value, $fail) use ($request, $assetHasZones) {
                    $exists = AssetTemplateVariable::where('variable_id', $value)
                        ->where('asset_template_id', $request->asset_template_id)
                        ->where(function ($query) use ($request, $assetHasZones) {
                            if ($assetHasZones && $request->filled('variable_template_zones')) {
                                $query->whereIn('template_zone_id', $request->variable_template_zones);
                            } else {
                                $query->whereNull('template_zone_id');
                            }
                        })->exists();

                    if ($exists) {
                        if ($request->filled('variable_template_zones') && $assetHasZones) {
                            $fail('The combination of Variable and Template Zone already exists.');
                        } else {
                            $fail('The combination of Variable and Template already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'variable_template_zones' => [
                $assetHasZones ? 'required' : 'nullable', 
                'array',
            ],
            'template_zones.*' => 'nullable|exists:template_zones,template_zone_id'
        ]);

        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable_type = $variable->variable_type_id;
        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['variable_type_id'] = $variable_type;

        $createdVariables = [];

        if (!empty($data['variable_template_zones'])) 
        {
            foreach ($data['variable_template_zones'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $variableData = $data;
                $variableData['template_zone_id'] = $zoneId;

                $assetVariable = AssetTemplateVariable::create($variableData);
                $createdVariables[] = new AssetTemplateVariableResource($assetVariable);

                foreach($request->asset_variable_attributes as $attribute)
                {
                    TemplateVariableValue::create([
                        'asset_template_variable_id' => $assetVariable->asset_template_variable_id,
                        'asset_template_id' => $assetVariable->asset_template_id,
                        'variable_id' => $variable->variable_id,
                        'template_zone_id' => $assetVariable->template_zone_id,
                        'variable_attribute_id' => $attribute['variable_attribute_id'],
                        'field_value' => $attribute['field_value'] ?? ''
                    ]);
                }
            }
        } 
        else 
        {
            $variableData = $data;
            $variableData['template_zone_id'] = null;

            $assetVariable = AssetTemplateVariable::create($variableData);
            $createdVariables[] = new AssetTemplateVariableResource($assetVariable);
            foreach($request->asset_variable_attributes as $attribute)
            {
                TemplateVariableValue::create([
                    'asset_template_variable_id' => $assetVariable->asset_template_variable_id,
                    'asset_template_id' => $assetVariable->asset_template_id,
                    'variable_id' => $variable->variable_id,
                    'template_zone_id' => $assetVariable->template_zone_id,
                    'variable_attribute_id' => $attribute['variable_attribute_id'],
                    'field_value' => $attribute['field_value'] ?? ''
                ]);
            }
        }

        return response()->json([$createdVariables, "message" => "AssetVariable Created Successfully"]);
    }

    public function getAssetTemplateVariable(Request $request)
    {
        $request->validate([
            'asset_template_variable_id' => 'required|exists:asset_template_variables,asset_template_variable_id'
        ]);

        $asset_variable = AssetTemplateVariable::where('asset_template_variable_id',$request->asset_template_variable_id)->first();
        return new AssetVariableResource($asset_variable);
    }

    public function updateAssetTemplateVariable(Request $request)
    {
        $asset_variables = AssetTemplateVariable::where('asset_template_variable_id', $request->asset_template_variable_id)->first();
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $data = $request->validate([
            'asset_template_variable_id' => 'required|exists:asset_template_variables,asset_template_variable_id',
            'variable_id' => [
                'required',
                'exists:variables,variable_id',
                function ($attribute, $value, $fail) use ($request, $asset_variables) 
                {
                    if ($value != $asset_variables->variable_id) {
                        $exists = AssetTemplateVariable::where('variable_id', $value)
                            ->where('asset_template_id', $request->asset_template_id)
                            ->where(function ($query) use ($request) {
                                if ($request->filled('template_zone_id')) {
                                    $query->where('template_zone_id', $request->template_zone_id);
                                } else {
                                    $query->whereNull('template_zone_id');
                                }
                            })->where('asset_template_variable_id', '!=', $request->asset_template_variable_id)->exists();
    
                        if ($exists) {
                            $fail('The combination of Service, Asset Template, and Template Zone already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'template_zone_id' => [
                $assetHasZones ? 'required' : 'nullable',
            ],
        ]);

        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable_type = $variable->variable_type_id;
        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['variable_type_id'] = $variable_type;

        $asset_variable = AssetTemplateVariable::where('asset_template_variable_id', $request->asset_template_variable_id)->first();
        $asset_variable->update($data);

        if(isset($request->deleted_asset_variable_values)>0)
        {
            TemplateVariableValue::whereIn('template_variable_value_id', $request->deleted_asset_variable_values)->forceDelete();
        }

        foreach ($request->asset_variable_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? '';

            if ($fieldValue !== null) {
                TemplateVariableValue::updateOrCreate(
                    [
                        'asset_template_variable_id' => $asset_variable->asset_template_variable_id,
                        'template_zone_id' => $asset_variable->template_zone_id,
                        'variable_id' => $variable->variable_id,
                        'asset_template_id' =>  $asset_variable->asset_template_id,
                        'variable_attribute_id' => $attribute['variable_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
        return response()->json([
            "message" => "Template Variable Updated Successfully",
            new AssetTemplateVariableResource($asset_variable)
        ]); 
    }

    public function deleteAssetTemplateVariable(Request $request)
    {
        $request->validate([
            'asset_template_variable_id' => 'required|exists:asset_template_variables,asset_template_variable_id'
        ]);

        TemplateVariableValue::where('asset_template_variable_id', $request->asset_template_variable_id)->delete();
        AssetTemplateVariable::where('asset_template_variable_id', $request->asset_template_variable_id)->delete();

        return response()->json([
            'message' => "Template Variable Deleted Successfully"
        ]);
    }
}
