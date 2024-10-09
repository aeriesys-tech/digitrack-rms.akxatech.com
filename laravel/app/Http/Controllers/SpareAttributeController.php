<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpareAttribute;
use App\Models\SpareAttributeType;
use App\Http\Resources\SpareAttributeResource;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpareAttributeExport;
use App\Exports\SpareAttributeHeadingsExport;
use App\Imports\SpareAttributesImport;

class SpareAttributeController extends Controller
{
    public function paginateSpareAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = SpareAttribute::query();

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

        if(isset($request->spare_type_id))
        {
            $query->where('spare_type_id',$request->spare_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "%$request->search%")
            ->orwhere('display_name', 'like', "%$request->search%")->orwhere('field_values', 'like', "%$request->search%")
            ->orwhere('field_type', 'like', "%$request->search%")->orwhere('field_length', 'like', "%$request->search%")
            ->orwhereHas('SpareAttributeTypes', function($que) use($request){
                $que->whereHas('SpareType', function($qu) use($request){
                    $qu->where('spare_type_name', 'like', "%$request->search%");
                });
            });    
        }
        $spare_attribute = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return SpareAttributeResource::collection($spare_attribute);
    }

    public function getSpareAttributes()
    {
        $spare_attribute = SpareAttribute::all();
        return SpareAttributeResource::collection($spare_attribute);
    }

    public function addSpareAttribute(Request $request)
    {
        $data = $request->validate([
            'field_name' => 'required|string|unique:spare_attributes,field_name',
            'display_name' => 'required|string|unique:spare_attributes,display_name',
            'field_type' => 'required', 
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'spare_types' => 'required|array',
            'spare_types.*' => 'required|exists:spare_types,spare_type_id'
        ]);

        $data['user_id'] = Auth::id();

        $spare_attribute = SpareAttribute::create($data);

        foreach ($data['spare_types'] as $spare_type) {
            SpareAttributeType::create([
                'spare_attribute_id' => $spare_attribute->spare_attribute_id,
                'spare_type_id' => $spare_type
            ]);
        }

        return new SpareAttributeResource($spare_attribute);  
    }

    public function getSpareAttribute(Request $request)
    {
        $request->validate([
            'spare_attribute_id' => 'required|exists:spare_attributes,spare_attribute_id'
        ]);

        $spare_attribute = SpareAttribute::where('spare_attribute_id', $request->spare_attribute_id)->first();
        return new SpareAttributeResource($spare_attribute);
    }

    public function getSparesDropdown(Request $request)
    {
        $request->validate([
            'spare_type_id' => 'required|exists:spare_type,spare_type_id'
        ]);

        $spare_type = SpareAttribute::whereHas('SpareAttributeTypes', function($que) use($request){
            $que->where('spare_type_id', $request->spare_type_id);
        })->get();

        return SpareAttributeResource::collection($spare_type);
    } 

    public function updateSpareAttribute(Request $request)
    {
        $data = $request->validate([
            'spare_attribute_id' => 'required|exists:spare_attributes,spare_attribute_id',
            'field_name' => 'required|string|unique:spare_attributes,field_name,'.$request->spare_attribute_id .',spare_attribute_id',
	        'display_name' => 'required|string|unique:spare_attributes,display_name,'.$request->spare_attribute_id .',spare_attribute_id',
            'field_type' => 'required',
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'spare_types' => 'required|array',
            'spare_types.*' => 'required|exists:spare_types,spare_type_id' 
        ]);

        $data['user_id'] = Auth::id();

        $spare_attribute = SpareAttribute::where('spare_attribute_id', $request->spare_attribute_id)->first();
        $spare_attribute->update($data);

        if(isset($request->deleted_spare_attribute_types) > 0)
        {
            SpareAttributeType::whereIn('spare_attribute_type_id', $request->deleted_spare_attribute_types)->forceDelete();
        }

        foreach ($data['spare_types'] as $spare_type_id) 
        {
            $spare_type = SpareAttributeType::where('spare_attribute_id', $spare_attribute->spare_attribute_id)->where('spare_type_id', $spare_type_id)->first();
            if($spare_type)
            {
                $spare_type->update([
                    'spare_type_id' => $spare_type_id
                ]);
            }
            else{
                SpareAttributeType::create([
                    'spare_attribute_id' => $spare_attribute->spare_attribute_id,
                    'spare_type_id' => $spare_type_id
                ]);
            }
        }
        return new SpareAttributeResource($spare_attribute);
    }

    public function deleteSpareAttribute(Request $request)
    {
        $request->validate([
            'spare_attribute_id' => 'required|exists:spare_attributes,spare_attribute_id'
        ]);

        $spare_attribute = SpareAttribute::withTrashed()->where('spare_attribute_id', $request->spare_attribute_id)->first();
       
        if($spare_attribute->trashed())
        {
            $spare_attribute->restore();
            return response()->json([
                "message" => "SpareAttribute Activated successfully"
            ],200);
        }
        else
        {
            $spare_attribute->delete();
            return response()->json([
                "message" => "SpareAttribute Deactivated successfully"
            ], 200); 
        }
    }

    public function downloadSpareAttributes(Request $request)
    {
        $filename = "Spare Attributes.xlsx";

        $excel = new SpareAttributeExport();

        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function downloadSpareAttributeHeadings()
    {
        $filename = "Spare Attribute Headings.xlsx";
        $excel = new SpareAttributeHeadingsExport();
        
        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importSpareAttribute(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new SpareAttributesImport, $request->file('file'));

        return response()->json(['success' => 'Data imported successfully!']);
    }
}
