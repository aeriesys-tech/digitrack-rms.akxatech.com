<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AssetTemplateService;
use App\Models\Service;
use App\Models\TemplateZone;
use App\Models\AssetTemplate;
use App\Models\TemplateServiceValue;
use App\Http\Resources\AssetTemplateServiceResource;
use App\Http\Resources\ServiceResource;

class AssetTemplateServiceController extends Controller
{
    public function paginateAssetTemplateServices(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
        ]);

        $query = AssetTemplateService::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->service_id))
        {
            $query->where('service_id',$request->service_id);
        }
        if(isset($request->asset_template_id))
        {
            $query->where('asset_template_id',$request->asset_template_id);
        }
        
        if($request->search!='')
        {
            $query->whereHas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orwhereHas('AssetTemplate', function($query) use($request){
                $query->where('template_name', 'like', "$request->search%");
            })->orwhereHas('Service', function($query) use($request){
                $query->where('service_name', 'like', "$request->search%");
            });
        }
        $template_service = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 

        //Dropdown Service
        $services = Service::whereHas('ServiceAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();

        return response()->json([
            'paginate_services' => AssetTemplateServiceResource::collection($template_service),
            'meta' => [
                'current_page' => $template_service->currentPage(),
                'last_page' => $template_service->lastPage(),
                'per_page' => $template_service->perPage(),
                'total' => $template_service->total(),
            ],
            'services' => ServiceResource::collection($services),
        ]);
    }

    public function addAssetTemplateService(Request $request)
    {
        // $userPlantId = Auth::User()->plant_id;
        // $areaId = Auth::User()->Plant->area_id;
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $data = $request->validate([
            'service_id' => [
                'required',
                'exists:services,service_id',
                function ($attribute, $value, $fail) use ($request, $assetHasZones) {
                    $exists = AssetTemplateService::where('service_id', $value)
                        ->where('asset_template_id', $request->asset_template_id)
                        ->where(function ($query) use ($request, $assetHasZones) {
                            if ($assetHasZones && $request->filled('service_template_zones')) {
                                $query->whereIn('template_zone_id', $request->service_template_zones);
                            } else {
                                $query->whereNull('template_zone_id');
                            }
                        })->exists();

                    if ($exists) {
                        if ($request->filled('service_template_zones') && $assetHasZones) {
                            $fail('The combination of Service and Template Zone already exists.');
                        } else {
                            $fail('The combination of Service and Template Zone already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'service_template_zones' => [
                $assetHasZones ? 'required' : 'nullable', 
                'array',
            ],
            'template_zones.*' => 'nullable|exists:template_zones,template_zone_id'
        ]);

        $service = Service::where('service_id', $request->service_id)->first();
        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['service_type_id'] = $service->service_type_id;

        $createdServices = [];

        if (!empty($data['service_template_zones'])) 
        {
            foreach ($data['service_template_zones'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $serviceData = $data;
                $serviceData['template_zone_id'] = $zoneId;

                $assetService = AssetTemplateService::create($serviceData);
                $createdServices[] = new AssetTemplateServiceResource($assetService);

                foreach($request->asset_service_attributes as $attribute)
                {
                    TemplateServiceValue::create([
                        'asset_template_service_id' => $assetService->asset_template_service_id,
                        'asset_template_id' => $assetService->asset_template_id,
                        'service_id' => $assetService->service_id,
                        'template_zone_id' => $assetService->template_zone_id,
                        'service_attribute_id' => $attribute['service_attribute_id'],
                        'field_value' => $attribute['field_value'] ?? ''
                    ]);
                }
            }
        } 
        // else 
        // {
        //     $serviceData = $data;
        //     $serviceData['template_zone_id'] = null;

        //     $assetService = AssetTemplateService::create($serviceData);
        //     $createdServices[] = new AssetTemplateServiceResource($assetService);

        //     foreach($request->asset_service_attributes as $attribute)
        //     {
        //         TemplateServiceValue::create([
        //             'asset_template_service_id' => $assetService->asset_template_service_id,
        //             'asset_template_id' => $assetService->asset_template_id,
        //             'service_id' => $assetService->service_id,
        //             'template_zone_id' => $assetService->template_zone_id,
        //             'service_attribute_id' => $attribute['service_attribute_id'],
        //             'field_value' => $attribute['field_value'] ?? ''
        //         ]);
        //     }
        // }
        return response()->json([$createdServices, "message" => "Template Service Created Successfully"]);
    }

    public function getAssetTemplateService(Request $request)
    {
        $request->validate([
            'asset_template_service_id' => 'required|exists:asset_template_services,asset_template_service_id'
        ]);

        $asset_service = AssetTemplateService::where('asset_template_service_id',$request->asset_template_service_id)->first();
        return new AssetTemplateServiceResource($asset_service);
    }

    public function updateAssetTemplateService(Request $request)
    {
        $asset_services = AssetTemplateService::where('asset_template_service_id', $request->asset_template_service_id)->first();
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $data = $request->validate([
            'asset_template_service_id' => 'required|exists:asset_template_services,asset_template_service_id',
            'service_id' => [
                'required',
                'exists:services,service_id',
                function ($attribute, $value, $fail) use ($request, $asset_services) 
                {
                    if ($value != $asset_services->service_id) {
                        $exists = AssetTemplateService::where('service_id', $value)
                            ->where('asset_template_id', $request->asset_template_id)
                            ->where(function ($query) use ($request) {
                                if ($request->filled('template_zone_id')) {
                                    $query->where('template_zone_id', $request->template_zone_id);
                                } else {
                                    $query->whereNull('template_zone_id');
                                }
                            })->where('asset_template_service_id', '!=', $request->asset_template_service_id)->exists();
    
                        if ($exists) {
                            $fail('The combination of Service, Asset Template, and Template Zone already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'template_zone_id' => [
                $assetHasZones ? 'required' : 'nullable',
            ],
        ]);

        $service = Service::where('service_id', $request->service_id)->first();
        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['service_type_id'] = $service->service_type_id;

        $asset_service = AssetTemplateService::where('asset_template_service_id', $request->asset_template_service_id)->first();
        $asset_service->update($data);

        if(isset($request->deleted_asset_service_values)>0)
        {
            TemplateServiceValue::whereIn('template_service_value_id', $request->deleted_asset_service_values)->forceDelete();
        }

        foreach ($request->asset_service_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? '';

            if ($fieldValue !== null) {
                TemplateServiceValue::updateOrCreate(
                    [
                        'asset_template_service_id' => $asset_service->asset_template_service_id,
                        'template_zone_id' => $asset_service->template_zone_id,
                        'service_id' => $service->service_id,
                        'asset_template_id' =>  $asset_service->asset_template_id,
                        'service_attribute_id' => $attribute['service_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
        return response()->json([
            "message" => "Template Service Updated Successfully",
            new AssetTemplateServiceResource($asset_service)
        ]); 
    }

    public function forceDeleteAssetTemplateService(Request $request)
    {
        $request->validate([
            'asset_template_service_id' => 'required|exists:asset_template_services,asset_template_service_id'
        ]);
    
        $asset_service = TemplateServiceValue::where('asset_template_service_id', $request->asset_template_service_id)->forceDelete();
        $asset_service = AssetTemplateService::where('asset_template_service_id', $request->asset_template_service_id)->forceDelete();
        return response()->json([
            "message" => "Template Service deleted successfully"
        ], 200);
    }    
}
