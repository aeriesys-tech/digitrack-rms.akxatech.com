<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariableAttribute;
use App\Models\VariableAttributeType;
use App\Http\Resources\VariableAttributeResource;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VariableAttributeExport;
use App\Exports\VariableAttributeHeadingsExport;
use App\Imports\VariableAttributesImport;

class VariableAttributeController extends Controller
{
    public function paginateVariableAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = VariableAttribute::query();

        if(isset($request->field_name))
        {
            $query->where('field_name',$request->field_name);
        }
        if(isset($request->display_name))
        {
            $query->where('display_name',$request->display_name);
        }
        if(isset($request->field_values))
        {
            $query->where('field_values',$request->field_values);
        }

        if(isset($request->variable_type_id))
        {
            $query->where('variable_type_id',$request->variable_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "%$request->search%")
            ->orwhere('display_name', 'like', "%$request->search%")->orwhere('field_values', 'like', "%$request->search%")
            ->orwhere('field_type', 'like', "%$request->search%")->orwhere('field_length', 'like', "%$request->search%")
            ->orwhereHas('VariableAttributeTypes', function($que) use($request){
                $que->whereHas('VariableType', function($qu) use($request){
                    $qu->where('variable_type_name', 'like', "%$request->search%");
                });
            });    
        }
        $variable_attribute = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return VariableAttributeResource::collection($variable_attribute);
    }

    public function getVariableAttributes()
    {
        $variable_attribute = VariableAttribute::all();
        return VariableAttributeResource::collection($variable_attribute);
    }

    public function addVariableAttribute(Request $request)
    {
        $data = $request->validate([
            'field_name' => 'required|string|unique:variable_attributes,field_name',
            'display_name' => 'required|string|unique:variable_attributes,display_name',
            'field_type' => 'required', 
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'variable_types' => 'required|array', 
            'variable_types.*' => 'required|exists:variable_types,variable_type_id' 
        ]);
    
        $data['user_id'] = Auth::id();
        $data['field_key'] = strtolower(trim(str_replace(' ', '_', $request->field_name)));
        
        $variable_attribute = VariableAttribute::create($data);
    
        foreach ($data['variable_types'] as $variable_type_id) {
            VariableAttributeType::create([
                'variable_attribute_id' => $variable_attribute->variable_attribute_id,
                'variable_type_id' => $variable_type_id
            ]);
        }
        return new VariableAttributeResource($variable_attribute);  
    }
    
    public function getVariableAttribute(Request $request)
    {
        $request->validate([
            'variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id'
        ]);

        $variable_attribute = VariableAttribute::where('variable_attribute_id', $request->variable_attribute_id)->first();
        return new VariableAttributeResource($variable_attribute);
    }

    public function updateVariableAttribute(Request $request)
    {
        $data = $request->validate([
            'variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id',
            'field_name' => 'required|string|unique:variable_attributes,field_name,'.$request->variable_attribute_id .',variable_attribute_id',
	        'display_name' => 'required|string|unique:variable_attributes,display_name,'.$request->variable_attribute_id .',variable_attribute_id',
            'field_type' => 'required',
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'variable_types' => 'required|array', 
            'variable_types.*' => 'required|exists:variable_types,variable_type_id' 
        ]);

        $data['user_id'] = Auth::id();
        $data['field_key'] = strtolower(trim(str_replace(' ', '_', $request->field_name)));

        $variable_attribute = VariableAttribute::where('variable_attribute_id', $request->variable_attribute_id)->first();
        $variable_attribute->update($data);

        if(isset($request->deleted_variable_attribute_types) > 0)
        {
            VariableAttributeType::whereIn('variable_attribute_type_id', $request->deleted_variable_attribute_types)->forceDelete();
        }

        foreach ($data['variable_types'] as $variable_type_id) 
        {
            $variableType = VariableAttributeType::where('variable_attribute_id', $variable_attribute->variable_attribute_id)
            ->where('variable_type_id', $variable_type_id)->first();

            if($variableType)
            {
                $variableType->update([
                    'variable_type_id' => $variable_type_id
                ]);
            }
            else {
                VariableAttributeType::create([
                    'variable_attribute_id' => $variable_attribute->variable_attribute_id,
                    'variable_type_id' => $variable_type_id
                ]);
            }
        }
        return new VariableAttributeResource($variable_attribute);
    }

    public function deleteVariableAttribute(Request $request)
    {
        $request->validate([
            'variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id'
        ]);

        $variable_attribute = VariableAttribute::withTrashed()->where('variable_attribute_id', $request->variable_attribute_id)->first();
       
        if($variable_attribute->trashed())
        {
            $variable_attribute->restore();
            return response()->json([
                "message" => "Variable Attribute Activated Successfully"
            ],200);
        }
        else
        {
            $variable_attribute->delete();
            return response()->json([
                "message" => "Variable Attribute Deactivated Successfully"
            ], 200); 
        }
    }

    public function downloadVariableAttributes(Request $request)
    {
        $filename = "Variable Attributes.xlsx";

        $excel = new VariableAttributeExport();

        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function downloadVariableAttributeHeadings()
    {
        $filename = "Variable Attribute Headings.xlsx";
        $excel = new VariableAttributeHeadingsExport();
        
        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importVariableAttribute(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new VariableAttributesImport, $request->file('file'));

        return response()->json(['success' => 'Data imported successfully!']);
    }
}
