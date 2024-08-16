<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceAssetType;
use App\Http\Resources\ServiceResource;

class ServiceController extends Controller
{
    public function paginateServices(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Service::query();

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
        return ServiceResource::collection($service);
    }

    public function getServices()
    {
        $service = Service::all();
        return ServiceResource::collection($service);
    }

    public function addService(Request $request)
    {
        $data = $request->validate([
            'service_type_id' => 'required|exists:service_type,service_type_id',
            'service_code' => 'required|string|unique:services,service_code',
            'service_name' => 'required|string|unique:services,service_name',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'frequency_id' => 'required|exists:frequencies,frequency_id'
        ]);
        
        $service = Service::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            ServiceAssetType::create([
                'service_id' => $service->service_id,
                'asset_type_id' => $asset_type,
            ]);
        }
        return response()->json(["message" => "Service Created Successfully"]);
    }  

    public function getService(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id'
        ]);

        $service = Service::where('service_id',$request->service_id)->first();
        return new ServiceResource($service);
    }

    public function getAssetTypeServices(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $services = Service::whereHas('ServiceAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return ServiceResource::collection($services);
    }

    public function updateService(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'service_type_id' => 'required|exists:service_type,service_type_id',
            'service_code' => 'required|unique:services,service_code,'.$request->service_id.',service_id',
            'service_name' => 'required|unique:services,service_name,'.$request->service_id.',service_id',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'frequency_id' => 'required|exists:frequencies,frequency_id'
        ]);

        $service = Service::where('service_id', $request->service_id)->first();
        $service->update($data);

        ServiceAssetType::where('service_id', $service->service_id)->delete();

        foreach ($data['asset_types'] as $asset_type_id) {
            ServiceAssetType::create([
                'service_id' => $service->service_id,
                'asset_type_id' => $asset_type_id
            ]);
        }
        return response()->json(["message" => "Service Updated Successfully"]);
    }

    public function deleteService(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id'
        ]);
        $service = Service::withTrashed()->where('service_id',$request->service_id)->first();

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
}

