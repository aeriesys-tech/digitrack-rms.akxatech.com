<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetServiceResource;
use App\Models\AssetService;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class AssetServiceController extends Controller
{
    public function paginateAssetServices(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
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
        return AssetServiceResource::collection($asset_service);
    }

    public function addAssetService(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $areaId = Auth::User()->Plant->area_id;
        $data = $request->validate([
            'service_id' => [
                'required',
                'exists:services,service_id',
                // function ($attribute, $value, $fail) use ($request) {
                //     $exists = AssetService::where('service_id', $value)
                //         ->where('asset_id', $request->asset_id)
                //         ->exists();
                //     if ($exists) {
                //         $fail('The combination of Service already exists.');
                //     }
                // },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zone_id' => 'nullable|array', 
            'asset_zone_id.*' => 'nullable|exists:asset_zones,asset_zone_id'
        ]);

        $data['plant_id'] = $userPlantId;
        $data['area_id'] = $areaId;

        $createdServices = [];

        if (!empty($data['asset_zone_id'])) 
        {
            foreach ($data['asset_zone_id'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $serviceData = $data;
                $serviceData['asset_zone_id'] = $zoneId;

                $assetService = AssetService::create($serviceData);
                $createdServices[] = new AssetServiceResource($assetService);
            }
        } 
        else 
        {
            $serviceData = $data;
            $serviceData['asset_zone_id'] = null;

            $assetService = AssetService::create($serviceData);
            $createdServices[] = new AssetServiceResource($assetService);
        }
        return response()->json($createdServices, 201);
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
            'asset_id' => 'required|exists:assets,asset_id'
        ]);
        $service_ids = AssetService::where('asset_id', $request->asset_id)->get('service_id')->toArray();
        $asset_service = Service::whereIn('service_id', $service_ids)->get();
        return $asset_service;
    }

    public function getAssetServices()
    {
        $asset_service = AssetService::all();
        return AssetServiceResource::collection($asset_service);
    }

    public function updateAssetService(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'asset_service_id' => 'required|exists:asset_services,asset_service_id',
            'spare_id' => 'required|exists:spares,spare_id',
            'asset_id' => 'required|exists:assets,asset_id',
            'area_id' => 'required|exists:areas,area_id',
            'asset_zone_id' => 'nullable|asset_zones,asset_zone_id',
            'service_type_id' => 'required|service_types,service_type_id'
        ]);

        $data['plant_id'] = $userPlantId;

        $asset_service = AssetService::where('asset_service_id', $request->asset_service_id)->first();
        $asset_service->update($data);
        return new AssetServiceResource($asset_service); 
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
}
