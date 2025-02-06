<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceAssetType;
use App\Http\Resources\ServiceResource;
use Auth;
use App\Models\ServiceAttribute;
use App\Models\ServiceAttributeValue;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ServiceExport;
use App\Exports\ServiceHeadingsExport;
use App\Imports\ServiceImport;

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
                ->orWhere('service_name', 'like', "%$request->search%")
                ->orwhereHas('ServiceType', function($qu) use($request){
                    $qu->where('service_type_name','like', "%$request->search%");
                })->orwhereHas('ServiceAssetTypes', function($qu) use($request){
                    $qu->whereHas('AssetType', function($que) use($request){
                        $que->where('asset_type_name','like', "%$request->search%");
                    });
                });
        }
        
        if ($request->keyword == 'service_type_name') {
            $query->join('service_type', 'services.service_type_id', '=', 'service_type.service_type_id')->select('services.*') 
                  ->orderBy('service_type.service_type_name', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }

        $service = $query->withTrashed()->paginate($request->per_page); 
        return ServiceResource::collection($service);
    }

    public function getServices()
    {
        $service = Service::all();
        return ServiceResource::collection($service);
    }

    public function addService(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'service_code' => 'required|string|unique:services,service_code',
            'service_name' => 'required|string|unique:services,service_name',
            'service_type_id' => 'required|exists:service_type,service_type_id',
            'service_attributes' => 'nullable|array',
            'service_attributes.*.service_attribute_id' => 'nullable|exists:service_attributes,service_attribute_id',
            'service_attributes.*.service_attribute_value.field_value' => 'nullable',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);
        $data['plant_id'] = $userPlantId;

        
        $service = Service::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            ServiceAssetType::create([
                'service_id' => $service->service_id,
                'asset_type_id' => $asset_type,
            ]);
        }

        foreach ($request->service_attributes as $attribute) 
        {
            ServiceAttributeValue::create([
                'service_id' => $service->service_id,
                'service_attribute_id' => $attribute['service_attribute_id'],
                'field_value' => $attribute['service_attribute_value']['field_value'] ?? '',
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

    public function getServiceData(Request $request)
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
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'service_code' => 'required|string|unique:services,service_code,' . $request->service_id . ',service_id',
            'service_name' => 'required|string|unique:services,service_name,' . $request->service_id . ',service_id',
            'service_type_id' => 'required|exists:service_type,service_type_id',
            'service_attributes' => 'nullable|array',
            'service_attributes.*.service_attribute_id' => 'nullable|exists:service_attributes,service_attribute_id',
            'service_attributes.*.service_attribute_value.field_value' => 'nullable',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'deleted_service_attribute_values' => 'nullable'
        ]);
    
        $data['plant_id'] = $userPlantId;
    
        $service = Service::where('service_id', $request->service_id)->first();
        $service->update($data);

        if(isset($request->deleted_service_asset_types) > 0)
        {
            ServiceAssetType::whereIn('service_asset_type_id', $request->deleted_service_asset_types)->forceDelete();
        }

        foreach ($data['asset_types'] as $asset_type) 
        {
            $serviceType = ServiceAssetType::where('service_id', $service->service_id)->where('asset_type_id', $asset_type)->first();
            if($serviceType)
            {
                $serviceType->update([
                    'asset_type_id' => $asset_type,
                ]);
            }
            else {
                ServiceAssetType::create([
                    'service_id' => $service->service_id,
                    'asset_type_id' => $asset_type,
                ]);
            }
        }

        if($request->deleted_service_attribute_values > 0)
        {
            ServiceAttributeValue::whereIn('service_attribute_value_id', $request->deleted_service_attribute_values)->forceDelete();
        }
    
        foreach ($request->service_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? $attribute['service_attribute_value']['field_value'] ?? null;
    
            if ($fieldValue !== null) {
                ServiceAttributeValue::updateOrCreate(
                    [
                        'service_id' => $service->service_id,
                        'service_attribute_id' => $attribute['service_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
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
                "message" =>"Service Activated Successfully"
            ],200);
        }
        else
        {
            $service->delete();
            return response()->json([
                "message" =>"Service Deactivated Successfully"
            ], 200); 
        }
    }

    public function downloadServices(Request $request)
    {
        $excel = new ServiceExport();

        return Excel::download($excel, 'Service.xlsx');
    }

    public function downloadServiceHeadings(Request $request)
    {
        $filename = "Service Headings.xlsx";
        $excel = new ServiceHeadingsExport($request->service_type_ids);
        
        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importService(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ServiceImport, $request->file('file'));

        return response()->json(['success' => 'Data imported successfully!']);
    }

    public function deleteHardService(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id'
        ]);

        ServiceAssetType::whereIn('service_id', $request->service_id)->forceDelete();
        ServiceAttributeValue::whereIn('service_id', $request->service_id)->forceDelete();
        Service::whereIn('service_id', $request->service_id)->forceDelete();

        return response()->json([
            "message" =>"Service Deleted Successfully"
        ],200);
    }
}