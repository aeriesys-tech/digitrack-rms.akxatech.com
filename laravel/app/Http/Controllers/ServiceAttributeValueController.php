<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceAttributeValue;
use App\Http\Resources\ServiceAttributeValueResource;

class ServiceAttributeValueController extends Controller
{
    public function paginateServiceAttributeValues(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = ServiceAttributeValue::query();

        if(isset($request->service_code))
        {
            $query->where('service_code',$request->service_code);
        }
        if(isset($request->service_name))
        {
            $query->where('service_name',$request->service_name);
        }
        
        if($request->search!='')
        {
            $query->where('service_code', 'like', "%$request->search%")
                ->orWhere('service_name', 'like', "$request->search%");
        }
        $service = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return ServiceAttributeValueResource::collection($service);
    }

    public function getServices()
    {
        $userPlantId = Auth::User()->plant_id;
    
        $service = Service::where('plant_id', $userPlantId)->get();
        return ServiceResource::collection($service);
    }

    public function addServiceAttributeValue(Request $request)
    {
        $data = $request->validate([
            'service_code' => 'required',
            'service_name' => 'required',
            'service_type_id' => 'required|exists:service_types,service_type_id',
            'service_attribute_id' => 'required|exists:service_attributes,service_attribute_id',
            'field_value' => 'required'
        ]);

        $service = ServiceAttributeValue::create($data);
        return new ServiceAttributeValueResource($service);
    }

    public function getService(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id'
        ]);

        $service = Service::where('service_id',$request->service_id)->first();
        return new ServiceResource($service);
    }

    public function getServiceCode(Request $request)
    {
        $request->validate([
            'service_code' => 'required'
        ]);

        $service = Service::where('service_code',$request->service_code)->first();
        return new ServiceResource($service);
    }

    public function updateService(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'service_code' => 'required|string|unique:services,service_code,'.$request->service_id.',service_id',
            'service_name' => 'required|string|unique:services,service_name,'.$request->service_id.',service_id',
            'service_type_id' => 'required|exists:service_types,service_type_id',
            'voltage_id' => 'required|exists:voltages,voltage_id',
            'watt_rating_id' => 'required|exists:watt_ratings,watt_rating_id',
            'frame_id' => 'required|exists:frames,frame_id',
            'mounting_id' => 'required|exists:mountings,mounting_id',
            'section_id' => 'required|exists:sections,section_id',
            'make_id' => 'required|exists:makes,make_id',
            'speed_id' => 'required|exists:speeds,speed_id',
            'serial_no' => 'nullable|sometimes'
        ]);
        $data['plant_id'] = $userPlantId;

        $service = Service::where('service_id', $request->service_id)->first();
        $service->update($data);
        return response()->json(["message" => "Service Updated Successfully"]);
    }

    public function deleteService(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id'
        ]);
        $service = Service::withTrashed()->where('service_id', $request->service_id)->first();

        if($service->trashed())
        {
            $service->restore();
            return response()->json([
                "message" =>"Service Activated successfully"
            ],200);
        }
        else
        {
            $service->delete();
            return response()->json([
                "message" =>"Service Deactivated successfully"
            ], 200); 
        }
    }

    public function getServicesDropdown(Request $request)
    {
        $request->validate([
            'service_type_id' => 'required|exists:service_types,service_type_id'
        ]);

        $service_type = ServiceAttribute::whereHas('ServiceAttributeTypes', function($que) use($request){
            $que->where('service_type_id', $request->service_type_id);
        })->get();

        return ServiceAttributeResource::collection($service_type);
    }
}