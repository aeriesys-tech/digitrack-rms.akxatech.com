<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceAttribute;
use App\Models\ServiceAttributeType;
use App\Http\Resources\ServiceAttributeResource;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ServiceAttributeExport;
use App\Exports\ServiceAttributeHeadingsExport;
use App\Imports\ServiceAttributesImport;

class ServiceAttributeController extends Controller
{
    public function paginateServiceAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = ServiceAttribute::query();

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

        if(isset($request->service_type_id))
        {
            $query->where('service_type_id',$request->service_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "%$request->search%")
            ->orwhere('display_name', 'like', "%$request->search%")->orwhere('field_values', 'like', "%$request->search%")
            ->orwhere('field_type', 'like', "%$request->search%")->orwhere('field_length', 'like', "%$request->search%")
            ->orwhereHas('ServiceAttributeTypes', function($que) use($request){
                $que->whereHas('ServiceType', function($qu) use($request){
                    $qu->where('service_type_name', 'like', "%$request->search%");
                });
            });    
        }
        $service_attribute = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return ServiceAttributeResource::collection($service_attribute);
    }

    public function getServiceAttributes()
    {
        $service_attribute = ServiceAttribute::all();
        return ServiceAttributeResource::collection($service_attribute);
    }

    public function addServiceAttribute(Request $request)
    {
        $data = $request->validate([
        	'field_name' => 'required|string|unique:service_attributes,field_name',
	        'display_name' => 'required|string|unique:service_attributes,display_name',
	        'field_type' => 'required', 
	        'field_values' => 'nullable|required_if:field_type,Dropdown',
	        'field_length' => 'required',
	        'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'service_types' => 'required|array',
	        'service_type_id.*' => 'required|exists:service_types,service_type_id'
        ]);
        $data['user_id'] = Auth::id();
        $data['field_key'] = strtolower(trim(str_replace(' ', '_', $request->field_name)));
        
        $service_attribute = ServiceAttribute::create($data);

        foreach ($data['service_types'] as $service_type_id) {
            ServiceAttributeType::create([
                'service_attribute_id' => $service_attribute->service_attribute_id,
                'service_type_id' => $service_type_id
            ]);
        }
        return new ServiceAttributeResource($service_attribute);  
    }  

    public function getServiceAttribute(Request $request)
    {
        $request->validate([
            'service_attribute_id' => 'required|exists:service_attributes,service_attribute_id'
        ]);

        $service_attribute = ServiceAttribute::where('service_attribute_id', $request->service_attribute_id)->first();
        return new ServiceAttributeResource($service_attribute);
    }

    public function updateServiceAttribute(Request $request)
    {
        $data = $request->validate([
            'service_attribute_id' => 'required|exists:service_attributes,service_attribute_id',
            'field_name' => 'required|string|unique:service_attributes,field_name,'.$request->service_attribute_id.',service_attribute_id',
	        'display_name' => 'required|string|unique:service_attributes,display_name,'.$request->service_attribute_id.',service_attribute_id',
            'field_type' => 'required',
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'service_types' => 'required|array',
            'service_types.*' => 'required|exists:service_type,service_type_id'
        ]);

        $data['user_id'] = Auth::id();
        $data['field_key'] = strtolower(trim(str_replace(' ', '_', $request->field_name)));

        $service_attribute = ServiceAttribute::where('service_attribute_id', $request->service_attribute_id)->first();
        $service_attribute->update($data);

        if(isset($request->deleted_service_attribute_types) > 0)
        {
            ServiceAttributeType::whereIn('service_attribute_type_id', $request->deleted_service_attribute_types)->forceDelete();
        }

        foreach ($data['service_types'] as $service_type_id) 
        {
            $serviceType = ServiceAttributeType::where('service_attribute_id', $service_attribute->service_attribute_id)
                ->where('service_type_id', $service_type_id)->first();

            if($serviceType)
            {
                $serviceType->update([
                    'service_type_id' => $service_type_id
                ]);
            }
            else{
                ServiceAttributeType::create([
                    'service_attribute_id' => $service_attribute->service_attribute_id,
                    'service_type_id' => $service_type_id
                ]);
            }
        }
        return new ServiceAttributeResource($service_attribute);
    }

    public function deleteServiceAttribute(Request $request)
    {
        $request->validate([
            'service_attribute_id' => 'required|exists:service_attributes,service_attribute_id'
        ]);

        $service_attribute = ServiceAttribute::withTrashed()->where('service_attribute_id', $request->service_attribute_id)->first();
       
        if($service_attribute->trashed())
        {
            $service_attribute->restore();
            return response()->json([
                "message" => "Service Attribute Activated Successfully"
            ],200);
        }
        else
        {
            $service_attribute->delete();
            return response()->json([
                "message" => "Service Attribute Deactivated Successfully"
            ], 200); 
        }
    }

    public function downloadServiceAttributes(Request $request)
    {
        $filename = "Service Attributes.xlsx";

        $excel = new ServiceAttributeExport();

        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function downloadServiceAttributeHeadings()
    {
        $filename = "Service Attribute Headings.xlsx";
        $excel = new ServiceAttributeHeadingsExport();
        
        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importServiceAttribute(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ServiceAttributesImport, $request->file('file'));

        return response()->json(['success' => 'Data imported successfully!']);
    }
}
