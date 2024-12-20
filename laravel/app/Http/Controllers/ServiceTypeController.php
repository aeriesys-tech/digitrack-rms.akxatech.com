<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceType;
use App\Http\Resources\ServiceTypeResource;

class ServiceTypeController extends Controller
{
    public function paginateServiceTypes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = ServiceType::query();

        if(isset($request->service_type_code))
        {
            $query->where('service_type_code',$request->service_type_code);
        }
        if(isset($request->service_type_name))
        {
            $query->where('service_type_name',$request->service_type_name);
        }
        
        if($request->search!='')
        {
            $query->where('service_type_code', 'like', "%$request->search%")
                ->orWhere('service_type_name', 'like', "%$request->search%");
        }
        $service_type = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return ServiceTypeResource::collection($service_type);
    }

    public function getServiceTypes()
    {
        $service_type = ServiceType::all();
        return ServiceTypeResource::collection($service_type);
    }

    public function addServiceType(Request $request)
    {
        $data = $request->validate([
            'service_type_code' => 'required|string|unique:service_type,service_type_code',
            'service_type_name' => 'required|string|unique:service_type,service_type_name'
        ]);
        
        $service_type = ServiceType::create($data);
        return response()->json(["message" => "Service Type Created Successfully"]); 
    }  

    public function getServiceType(Request $request)
    {
        $request->validate([
            'service_type_id' => 'required|exists:service_type,service_type_id'
        ]);

        $service_type = ServiceType::where('service_type_id',$request->service_type_id)->first();
        return new ServiceTypeResource($service_type);
    }

    public function updateServiceType(Request $request)
    {
        $data = $request->validate([
            'service_type_id' => 'required|exists:service_type,service_type_id',
            'service_type_code' => 'required|unique:service_type,service_type_code,'.$request->service_type_id.',service_type_id',
            'service_type_name' => 'required|unique:service_type,service_type_name,'.$request->service_type_id.',service_type_id'
        ]);

        $service_type = ServiceType::where('service_type_id', $request->service_type_id)->first();
        $service_type->update($data);
        return response()->json(["message" => "Service Type Updated Successfully"]);  
    }

    public function deleteServiceType(Request $request)
    {
        $request->validate([
            'service_type_id' => 'required|exists:service_type,service_type_id'
        ]);
        $service_type = ServiceType::withTrashed()->where('service_type_id',$request->service_type_id)->first();

        if($service_type->trashed())
        {
            $service_type->restore();
            return response()->json([
                "message" =>"Service Type Activated Successfully"
            ],200);
        }
        else
        {
            $service_type->delete();
            return response()->json([
                "message" =>"Service Type Deactivated Successfully"
            ], 200); 
        }
    }
}
