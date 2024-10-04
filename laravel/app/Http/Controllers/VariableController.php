<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variable;
use App\Models\VariableAssetType;
use App\Http\Resources\VariableResource;
use Auth;
use App\Models\VariableAttributeValue;
use App\Models\VariableAttribute;
use App\Http\Resources\VariableAttributeResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VariableExport;
use App\Exports\VariableHeadingsExport;
use App\Imports\VariableImport;

class VariableController extends Controller
{
    public function paginateVariables(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Variable::query();

        if(isset($request->variable_code))
        {
            $query->where('variable_code',$request->variable_code);
        }
        if(isset($request->variable_name))
        {
            $query->where('variable_name',$request->variable_name);
        }
              
        if($request->search!='')
        {
            $query->where('variable_code', 'like', "%$request->search%")
                ->orWhere('variable_name', 'like', "%$request->search%")
                ->orwhereHas('VariableType', function($qu) use($request) {
                    $qu->where('variable_type_name','like', "%$request->search%");
                })->orwhereHas('VariableAssetTypes', function($qu) use($request){
                    $qu->whereHas('AssetType', function($que) use($request) {
                        $que->where('asset_type_name','like', "%$request->search%");
                    });
                });
        }

        if ($request->keyword == 'variable_type_name') {
            $query->join('variable_types', 'variables.variable_type_id', '=', 'variable_types.variable_type_id')->select('variables.*') 
                  ->orderBy('variable_types.variable_type_name', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }

        $variable = $query->withTrashed()->paginate($request->per_page); 
        return VariableResource::collection($variable);
    }

    public function getVariables()
    {
        $variable = Variable::all();
        return VariableResource::collection($variable);
    }

    public function addVariable(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'variable_code' => 'required|string|unique:variables,variable_code',
            'variable_name' => 'required|string|unique:variables,variable_name',
            'variable_type_id' => 'required|exists:variable_types,variable_type_id',
            'variable_attributes' => 'nullable|array',
            'variable_attributes.*.variable_attribute_id' => 'nullable|exists:variable_attributes,variable_attribute_id',
            'variable_attributes.*.variable_attribute_value.field_value' => 'nullable',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);
        $data['plant_id'] = $userPlantId;

        
        $variable = Variable::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            VariableAssetType::create([
                'variable_id' => $variable->variable_id,
                'asset_type_id' => $asset_type,
            ]);
        }

        foreach ($request->variable_attributes as $attribute) {
            VariableAttributeValue::create([
                'variable_id' => $variable->variable_id,
                'variable_attribute_id' => $attribute['variable_attribute_id'],
                'field_value' => $attribute['variable_attribute_value']['field_value'] ?? '',
            ]);
        }
        
        return response()->json(["message" => "Variable Created Successfully"]);
    }

    public function getVariable(Request $request)
    {
        $request->validate([
            'variable_id' => 'required|exists:variables,variable_id'
        ]);

        $variable = Variable::where('variable_id',$request->variable_id)->first();
        return new VariableResource($variable);
    }

    public function getVariableData(Request $request)
    {
        $request->validate([
            'variable_id' => 'required|exists:variables,variable_id'
        ]);

        $variable = Variable::where('variable_id',$request->variable_id)->first();
        return new VariableResource($variable);
    }

    public function getAssetTypeVariables(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $variables = Variable::whereHas('VariableAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return VariableResource::collection($variables);
    }

    public function updateVariable(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'variable_id' => 'required|exists:variables,variable_id',
            'variable_code' => 'required|string|unique:variables,variable_code,' . $request->variable_id . ',variable_id',
            'variable_name' => 'required|string|unique:variables,variable_name,' . $request->variable_id . ',variable_id',
            'variable_type_id' => 'required|exists:variable_types,variable_type_id',
            'variable_attributes' => 'nullable|array',
            'variable_attributes.*.variable_attribute_id' => 'nullable|exists:variable_attributes,variable_attribute_id',
            'variable_attributes.*.variable_attribute_value.field_value' => 'nullable',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'deleted_variable_attribute_values' => 'nullable'
        ]);
    
        $data['plant_id'] = $userPlantId;
    
        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable->update($data);

        if(isset($request->deleted_variable_asset_types) > 0)
        {
            VariableAssetType::whereIn('variable_asset_type_id', $request->deleted_variable_asset_types)->forceDelete();
        }

        foreach ($data['asset_types'] as $asset_type) 
        {
            $variableType = VariableAssetType::where('variable_id', $variable->variable_id)->where('asset_type_id', $asset_type)->first();
            if($variableType)
            {
                $variableType->update([
                    'asset_type_id' => $asset_type,
                ]);
            }
            else {
                VariableAssetType::create([
                    'variable_id' => $variable->variable_id,
                    'asset_type_id' => $asset_type,
                ]);
            }
        }

        if($request->deleted_variable_attribute_values > 0)
        {
            VariableAttributeValue::whereIn('variable_attribute_value_id', $request->deleted_variable_attribute_values)->forceDelete();
        }
    
        foreach ($request->variable_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? $attribute['variable_attribute_value']['field_value'] ?? null;
    
            if ($fieldValue !== null) {
                VariableAttributeValue::updateOrCreate(
                    [
                        'variable_id' => $variable->variable_id,
                        'variable_attribute_id' => $attribute['variable_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
        return response()->json(["message" => "Variable Updated Successfully"]);
    } 

    public function deleteVariable(Request $request)
    {
        $request->validate([
            'variable_id' => 'required|exists:variables,variable_id'
        ]);
        $variable = Variable::withTrashed()->where('variable_id', $request->variable_id)->first();

        if($variable->trashed())
        {
            $variable->restore();
            return response()->json([
                "message" => "Variable Activated successfully"
            ],200);
        }
        else
        {
            $variable->delete();
            return response()->json([
                "message" => "Variable Deactivated successfully"
            ], 200);
        }
    }

    public function getVariablesDropdown(Request $request)
    {
        $request->validate([
            'variable_type_id' => 'required|exists:variable_types,variable_type_id'
        ]);

        $variable_type = VariableAttribute::whereHas('VariableAttributeTypes', function($que) use($request){
            $que->where('variable_type_id', $request->variable_type_id);
        })->get();

        return VariableAttributeResource::collection($variable_type);
    }

    public function downloadVariables(Request $request)
    {
        $filename = "Variable.xlsx";

        $excel = new VariableExport();

        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function downloadVariableHeadings(Request $request)
    {
        $filename = "Variable Headings.xlsx";
        $excel = new VariableHeadingsExport($request->variable_type_ids);
        
        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importVariable(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new VariableImport, $request->file('file'));

        return response()->json(['success' => 'Data imported successfully!']);
    }
}
