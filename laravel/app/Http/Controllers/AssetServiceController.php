<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetServiceResource;
use App\Models\AssetService;
use App\Models\Service;
use App\Models\AssetZone;
use App\Models\Asset;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\AssetZoneResource;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetServiceValue;
use App\Models\ServiceAttributeValue;
use App\Http\Resources\ServiceAttributeValueResource;

class AssetServiceController extends Controller
{
    public function paginateAssetServices(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
        ]);

        $query = AssetService::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->service_id))
        {
            $query->where('service_id',$request->service_id);
        }
        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }
        
        if($request->search!='')
        {
            $query->whereHas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orwhereHas('Asset', function($query) use($request){
                $query->where('asset_name', 'like', "$request->search%");
            })->orwhereHas('Service', function($query) use($request){
                $query->where('service_name', 'like', "$request->search%");
            });
        }
        $asset_service = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 

        //Dropdown Service
        $services = Service::whereHas('ServiceAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();

        return response()->json([
            'paginate_services' => AssetServiceResource::collection($asset_service),
            'meta' => [
                'current_page' => $asset_service->currentPage(),
                'last_page' => $asset_service->lastPage(),
                'per_page' => $asset_service->perPage(),
                'total' => $asset_service->total(),
            ],
            'services' => ServiceResource::collection($services),
        ]);
    }

    public function addAssetService(Request $request)
    {
        // $userPlantId = Auth::User()->plant_id;
        // $areaId = Auth::User()->Plant->area_id;
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $data = $request->validate([
            // 'service_id' => [
            //     'required',
            //     'exists:services,service_id',
            //     function ($attribute, $value, $fail) use ($request, $assetHasZones) {
            //         $exists = AssetService::where('service_id', $value)
            //             ->where('asset_id', $request->asset_id)
            //             ->where(function ($query) use ($request, $assetHasZones) {
            //                 if ($assetHasZones && $request->filled('service_asset_zones')) {
            //                     $query->whereIn('asset_zone_id', $request->service_asset_zones);
            //                 } else {
            //                     $query->whereNull('asset_zone_id');
            //                 }
            //             })->exists();

            //         if ($exists) {
            //             if ($request->filled('service_asset_zones') && $assetHasZones) {
            //                 $fail('The combination of Service and Asset Zone already exists.');
            //             } else {
            //                 $fail('The combination of Service and Asset already exists.');
            //             }
            //         }
            //     },
            // ],
            'service_id' => 'required|exists:services,service_id',
            'asset_id' => 'required|exists:assets,asset_id',
            // 'service_asset_zones' => [
            //     $assetHasZones ? 'required' : 'nullable', 
            //     'array',
            // ],
            // 'asset_zones.*' => 'nullable|exists:asset_zones,asset_zone_id'
        ]);

        $service = Service::where('service_id', $request->service_id)->first();
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['service_type_id'] = $service->service_type_id;

        // $createdServices = [];
        // if (!empty($data['service_asset_zones'])) 
        // {
        //     foreach ($data['service_asset_zones'] as $zoneId) 
        //     {              
        //         if (is_null($zoneId) || $zoneId == 0) 
        //         {
        //             continue;
        //         }

        //         $serviceData = $data;
        //         $serviceData['asset_zone_id'] = $zoneId;

        //         $assetService = AssetService::create($serviceData);
        //         $createdServices[] = new AssetServiceResource($assetService);

        //         foreach($request->asset_service_attributes as $attribute)
        //         {
        //             AssetServiceValue::create([
        //                 'asset_service_id' => $assetService->asset_service_id,
        //                 'asset_id' => $assetService->asset_id,
        //                 'service_id' => $assetService->service_id,
        //                 'asset_zone_id' => $assetService->asset_zone_id,
        //                 'service_attribute_id' => $attribute['service_attribute_id'],
        //                 'field_value' => $attribute['field_value'] ?? ''
        //             ]);
        //         }
        //     }
        // } 

        $assetService = AssetService::create($data);
        
        foreach($request->asset_service_attributes as $attribute)
        {
            AssetServiceValue::create([
                'asset_service_id' => $assetService->asset_service_id,
                'asset_id' => $assetService->asset_id,
                'service_id' => $assetService->service_id,
                // 'asset_zone_id' => $assetService->asset_zone_id,
                'service_attribute_id' => $attribute['service_attribute_id'],
                'field_value' => $attribute['field_value'] ?? ''
            ]);
        }
        
        return response()->json([
            new AssetServiceResource($assetService),
            "message" => "AssetService Created Successfully"
        ]);
    }

    public function getAssetService(Request $request)
    {
        $request->validate([
            'asset_service_id' => 'required|exists:asset_services,asset_service_id'
        ]);

        $asset_service = AssetService::where('asset_service_id',$request->asset_service_id)->first();
        return new AssetServiceResource($asset_service);
    }

    public function getAssetsServices(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id'
        ]);

        $query = AssetService::where('asset_id', $request->asset_id);

        if ($request->has('asset_zone_id') && $request->asset_zone_id !== null) {
            $query->where('asset_zone_id', $request->asset_zone_id);
        }

        $service_ids = $query->pluck('service_id')->toArray();
        $asset_services = Service::whereIn('service_id', $service_ids)->get();

        return response()->json($asset_services);
    }

    public function getAssetServices()
    {
        $asset_service = AssetService::all();
        return AssetServiceResource::collection($asset_service);
    }

    public function updateAssetService(Request $request)
    {
        $asset_services = AssetService::where('asset_service_id', $request->asset_service_id)->first();
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $data = $request->validate([
            'asset_service_id' => 'required|exists:asset_services,asset_service_id',
            // 'service_id' => [
            //     'required',
            //     'exists:services,service_id',
            //     function ($attribute, $value, $fail) use ($request, $asset_services) 
            //     {
            //         if ($value != $asset_services->service_id) {
            //             $exists = AssetService::where('service_id', $value)
            //                 ->where('asset_id', $request->asset_id)
            //                 ->where(function ($query) use ($request) {
            //                     if ($request->filled('asset_zone_id')) {
            //                         $query->where('asset_zone_id', $request->asset_zone_id);
            //                     } else {
            //                         $query->whereNull('asset_zone_id');
            //                     }
            //                 })->where('asset_service_id', '!=', $request->asset_service_id)->exists();
    
            //             if ($exists) {
            //                 $fail('The combination of Service, Asset, and Asset Zone already exists.');
            //             }
            //         }
            //     },
            // ],
            'service_id' => 'required|exists:services,service_id',
            'asset_id' => 'required|exists:assets,asset_id',
            // 'asset_zone_id' => [
            //     $assetHasZones ? 'required' : 'nullable',
            // ],
        ]);

        $service = Service::where('service_id', $request->service_id)->first();
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['service_type_id'] = $service->service_type_id;

        $asset_service = AssetService::where('asset_service_id', $request->asset_service_id)->first();
        $asset_service->update($data);

        if(isset($request->deleted_asset_service_values)>0)
        {
            AssetServiceValue::whereIn('asset_service_value_id', $request->deleted_asset_service_values)->forceDelete();
        }

        foreach ($request->asset_service_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? '';

            if ($fieldValue !== null) {
                AssetServiceValue::updateOrCreate(
                    [
                        'asset_service_id' => $asset_service->asset_service_id,
                        // 'asset_zone_id' => $asset_service->asset_zone_id,
                        'service_id' => $service->service_id,
                        'asset_id' =>  $asset_service->asset_id,
                        'service_attribute_id' => $attribute['service_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
        return response()->json([
            "message" => "AssetService Updated Successfully",
            new AssetServiceResource($asset_service)
        ]); 
    }

    public function deleteAssetService(Request $request)
    {
        $request->validate([
            'asset_service_id' => 'required|exists:asset_services,asset_service_id'
        ]);
        $asset_service = AssetService::withTrashed()->where('asset_service_id', $request->asset_service_id)->first();

        if($asset_service->trashed())
        {
            $asset_service->restore();
            return response()->json([
                "message" =>"AssetService Activated successfully"
            ],200);
        }
        else
        {
            $asset_service->delete();
            return response()->json([
                "message" =>"AssetService Deactivated successfully"
            ], 200); 
        }
    }

    public function forceDeleteAssetService(Request $request)
    {
        $request->validate([
            'asset_service_id' => 'required|exists:asset_services,asset_service_id'
        ]);
    
        try {
            $asset_service = AssetService::where('asset_service_id', $request->asset_service_id)->forceDelete();
            return response()->json([
                "message" => "AssetService deleted successfully"
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                "error" => "Cannot delete AssetService because it is associated with other records."
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "An unexpected error occurred. Please try again."
            ], 500);
        }
    }    

    public function assetServiceAttributeValues(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id'
        ]);

        $service_attribute_values = ServiceAttributeValue::where('service_id', $request->service_id)->get();
        return ServiceAttributeValueResource::collection($service_attribute_values);
    }
}
