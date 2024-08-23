<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceAttribute;
use App\Models\ServiceAttributeType;
use App\Http\Resources\ServiceAttributeResource;
use Illuminate\Support\Facades\Auth;

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
            $query->where('field_name', 'like', "$request->search%")
            ->orwhere('display_name', 'like', "$request->search%")->orwhere('field_values', 'like', "$request->search%")
            ->orwhere('field_type', 'like', "$request->search%")->orwhere('field_length', 'like', "$request->search%")
            ->orwhereHas('ServiceAttributeTypes', function($que) use($request){
                $que->whereHas('ServiceType', function($qu) use($request){
                    $que->where('service_type_name', 'like', "$request->search%");
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
        	'field_name' => 'required',
	        'display_name' => 'required',
	        'field_type' => 'required', 
	        'field_values' => 'nullable',
	        'field_length' => 'required',
	        'is_required' => 'required|boolean',
            'service_types' => 'required|array',
	        'service_type_id.*' => 'required|exists:service_types,service_type_id'
        ]);
        $data['user_id'] = Auth::id();
        
        $service_attribute = ServiceAttribute::create($data);

        foreach ($data['service_types'] as $service_tpe_id) {
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
            'field_name' => 'required',
            'display_name' => 'required',
            'field_type' => 'required',
            'field_values' => 'nullable',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'service_types' => 'required|array',
            'service_types.*' => 'required|exists:service_type,service_type_id'
        ]);

        $data['user_id'] = Auth::id();

        $service_attribute = ServiceAttribute::where('service_attribute_id', $request->service_attribute_id)->first();
        $service_attribute->update($data);

        ServiceAttributeType::where('service_attribute_id', $service_attribute->service_attribute_id)->delete();

        foreach ($data['service_types'] as $service_type_id) {
            ServiceAttributeType::create([
                'service_attribute_id' => $service_attribute->service_attribute_id,
                'service_type_id' => $service_type_id
            ]);
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
                "message" => "ServiceAttribute Activated successfully"
            ],200);
        }
        else
        {
            $service_attribute->delete();
            return response()->json([
                "message" => "ServiceAttribute Deactivated successfully"
            ], 200); 
        }
    }
}
