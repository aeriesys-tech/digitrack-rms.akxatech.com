<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\WattRating;
use App\Models\AssetAttributeType;
use App\Models\Section;
use App\Models\Plant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Milon\Barcode\DNS2D;
use PDF;
use App\Http\Resources\AssetAttributeResource;
use App\Models\AssetAttribute;
use App\Models\AssetAttributeValue;
use App\Http\Resources\AssetAttributeValueResource;
use App\Models\CampaignResult;
use App\Models\AssetZone;
use App\Http\Resources\AssetZoneResource;
use App\Models\AssetDepartment;
use App\Models\AssetSpare;
use App\Models\AssetCheck;
use App\Models\AssetService;
use App\Models\AssetVariable;
use App\Models\AssetDataSource;
use App\Models\AssetAccessory;
use App\Models\AssetSpareValue;
use App\Models\AssetTemplateService;
use App\Models\AssetServiceValue;
use App\Models\AssetVariableValue;
use App\Models\AssetDataSourceValue;
use App\Models\BreakDownList;
use App\Models\Campaign;
use App\Models\UserActivity;
use App\Models\UserService;
use App\Models\UserCheck;
use App\Models\UserVariable;
use App\Models\AssetTemplateSpare;
use App\Models\TemplateZone;
use App\Models\AssetTemplateCheck;
use App\Models\AssetTemplateVariable;
use App\Models\AssetTemplateDataSource;
use App\Models\AssetTemplateAccessory;

class AssetController extends Controller
{
    public function paginateAssets(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        // $authPlantId = Auth::User()->plant_id;
        $query = Asset::query();

        // $query->where('plant_id', $authPlantId);

        if(isset($request->asset_type_id))
        {
            $query->where('asset_type_id',$request->asset_type_id);
        }

        if(isset($request->asset_code))
        {
            $query->where('asset_code',$request->asset_code);
        }

        if(isset($request->asset_name))
        {
            $query->where('asset_name',$request->asset_name);
        }
        
        if($request->search!='')
        {
            $query->where('asset_code', 'like', "%$request->search%")
                ->orWhere('asset_name', 'like', "%$request->search%")
                ->orWhereHas('AssetType', function ($q) use ($request) {
                    $q->where('asset_type_code', 'like', "%$request->search%")
                    ->orWhere('asset_type_name', 'like', "%$request->search%");
                });
        }
        if ($request->keyword == 'asset_type_name') {
            $query->join('asset_type', 'assets.asset_type_id', '=', 'asset_type.asset_type_id')->select('assets.*') 
                  ->orderBy('asset_type.asset_type_name', $request->order_by);
        }
        elseif ($request->keyword == 'reason_code') {
            $query->join('reasons', 'user_activities.reason_id', '=', 'reasons.reason_id')->select('user_activities.*') 
                  ->orderBy('reasons.reason_code', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }
        $asset = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AssetResource::collection($asset);
    }

    public function addAsset(Request $request)
    {
        $data = $request->validate([
            'asset_code' => 'required|string|unique:assets,asset_code',
            'asset_name' => 'required|string|unique:assets,asset_name',
            'no_of_zones' => 'required|integer',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_attributes' => 'nullable|array',
            'asset_attributes.*.asset_attribute_id' => 'nullable|exists:asset_attributes,asset_attribute_id',
            'asset_attributes.*.asset_attribute_value.field_value' => 'nullable',
            'longitude' => 'nullable|sometimes|numeric',
            'latitude' => 'nullable|sometimes|numeric',
            'functional_id' => 'nullable|exists:functionals,functional_id',
            'section_id' => 'nullable|exists:sections,section_id',
            'radius' => 'nullable|sometimes|numeric',
            'zone_name' => 'nullable|array', 
            'zone_name.*' => 'nullable',
            'plant_id' => 'required|exists:plants,plant_id',
            'area_id' => 'nullable|exists:areas,area_id',
            'geometry_type' => 'nullable',
            'height' => 'nullable|required_if:geometry_type,V-Cylindrical,H-Cylindrical',
            'diameter' => 'nullable|required_if:geometry_type,V-Cylindrical,H-Cylindrical',
            'asset_template_id' => 'nullable|exists:asset_templates,asset_template_id'
        ]);
     
        $request->validate([
            'zone_name' => function ($attribute, $value, $fail) use ($request) {
                if (isset($value)) {
                    $totalHeight = 0;
                    $assetDiameter = (float) $request->input('diameter', 0); 
                    $assetHeight = (float) $request->input('height', 0);
                    
                    foreach ($value as $zone) {
                        $zoneHeight = isset($zone['height']) ? (float) $zone['height'] : 0;
                        $zoneDiameter = isset($zone['diameter']) ? (float) $zone['diameter'] : 0;
                        $zoneName = $zone['zone_name'] ?? '';
        
                        if ($zoneName !== 'Overall') {
                                $totalHeight += $zoneHeight;
                            } else {
                                $overallZoneHeight = $zoneHeight;
                            }
        
                        if ($zoneDiameter != $assetDiameter) {
                            $fail("The diameter of each AssetZones must be equal to the Asset's diameter.");
                            return; 
                        }
                    }
        
                    if ($totalHeight != $assetHeight) {
                        $fail("The total height of AssetZones (excluding Overall) must be equal to the Asset's height.");
                    }
                
                    if ($overallZoneHeight !== $assetHeight) {
                        $fail("The height of the Overall zone must be equal to the Asset's height.");
                    }
                }
            }
        ]);             

        $asset = Asset::create($data);

        if (isset($request->asset_departments)) 
        {
            foreach ($request->asset_departments as $department) {
                AssetDepartment::updateOrCreate([
                    'asset_id' => $asset->asset_id,
                    'department_id' => $department,
                ]);
            }
        }

        if (isset($request->asset_attributes)) {
            foreach ($request->asset_attributes as $attribute) {
                AssetAttributeValue::updateOrCreate(
                    [
                        'asset_id' => $asset->asset_id,
                        'asset_attribute_id' => $attribute['asset_attribute_id'],
                    ],
                    [
                        'field_value' => $attribute['asset_attribute_value']['field_value'] ?? '',
                    ]
                );
            }
        }

        $no_of_zones = $request->no_of_zones;
        $zoneNames = $request->zone_name;

        foreach ($zoneNames as $zoneName) {
            AssetZone::updateOrCreate(
                [
                    'asset_id' => $asset->asset_id,
                    'zone_name' => $zoneName['zone_name']
                ],
                [
                    'height' => $zoneName['height'] ?? null,
                    'diameter' => $zoneName['diameter'] ?? null
                ]
            );
        }

        //duplicate AssetTemplate to Asset
        if ($asset->asset_template_id) 
        {
            //AssetSpares
            $template_spares = AssetTemplateSpare::where('asset_template_id', $asset->asset_template_id)->get();
            foreach ($template_spares as $spare) 
            {
                $templateZone = TemplateZone::where('template_zone_id', $spare->template_zone_id)->first();
                if ($templateZone) 
                {
                    $assetZone = AssetZone::where('asset_id', $asset->asset_id)->where('zone_name', $templateZone->zone_name)->first();
                    if ($assetZone) 
                    {
                        $newSpare = AssetSpare::create([
                            'asset_id' => $asset->asset_id,
                            'area_id' => $asset->area_id,
                            'plant_id' => $asset->plant_id,
                            'spare_id' => $spare->spare_id,
                            'spare_type_id' => $spare->spare_type_id,
                            'quantity' => $spare->quantity,
                            'asset_zone_id' => $assetZone->asset_zone_id
                        ]);
        
                        foreach ($spare->TemplateSpareValue as $spare_value) 
                        {
                            AssetSpareValue::create([
                                'asset_spare_id' => $newSpare->asset_spare_id,
                                'asset_id' => $asset->asset_id,
                                'asset_zone_id' => $assetZone->asset_zone_id,
                                'spare_id' => $spare_value->spare_id,
                                'spare_attribute_id' => $spare_value->spare_attribute_id,
                                'field_value' => $spare_value->field_value
                            ]);
                        }
                    }
                }
            }

            //AssetService
            $template_services = AssetTemplateService::where('asset_template_id', $asset->asset_template_id)->get();
            foreach ($template_services as $service) 
            {
                $templateZone = TemplateZone::where('template_zone_id', $service->template_zone_id)->first();
                if ($templateZone) 
                {
                    $assetZone = AssetZone::where('asset_id', $asset->asset_id)->where('zone_name', $templateZone->zone_name)->first();
                    if ($assetZone) 
                    {
                        $newService = AssetService::create([
                            'asset_id' => $asset->asset_id,
                            'area_id' => $asset->area_id,
                            'plant_id' => $asset->plant_id,
                            'service_id' => $service->service_id,
                            'asset_zone_id' => $assetZone->asset_zone_id,
                            'service_type_id' => $service->service_type_id
                        ]);

                        foreach ($service->TemplateServiceValue as $service_value) 
                        {
                            AssetServiceValue::create([
                                'asset_service_id' => $newService->asset_service_id,
                                'asset_id' => $asset->asset_id,
                                'asset_zone_id' => $assetZone->asset_zone_id,
                                'service_id' => $service_value->service_id,
                                'service_attribute_id' => $service_value->service_attribute_id,
                                'field_value' => $service_value->field_value
                            ]);
                        }
                    }
                }
            }

            //AssetCheck
            $template_checks = AssetTemplateCheck::where('asset_template_id', $asset->asset_template_id)->get();
            foreach ($template_checks as $check) 
            {
                $templateZone = TemplateZone::where('template_zone_id', $check->template_zone_id)->first();
                $service = AssetTemplateService::where('asset_template_service_id', $check->asset_template_service_id)->with('Service')->first();

                if ($templateZone && $service) 
                {
                    $assetZone = AssetZone::where('asset_id', $asset->asset_id)->where('zone_name', $templateZone->zone_name)->first();
                    $serviceName = AssetService::where('asset_id', $asset->asset_id)
                        ->whereHas('Service', function ($qur) use ($service) {
                            $qur->where('service_name', $service->Service->service_name);
                        })->first();

                    if ($assetZone && $serviceName) 
                    {
                        $newCheck = AssetCheck::create([
                            'asset_id' => $asset->asset_id,
                            'area_id' => $asset->area_id,
                            'plant_id' => $asset->plant_id,
                            'check_id' => $check->check_id,
                            'asset_zone_id' => $assetZone->asset_zone_id,
                            'default_value' => $check->default_value,
                            'lcl' => $check->lcl,
                            'ucl' => $check->ucl,
                            'asset_service_id' =>$serviceName->asset_service_id
                        ]);
                    }
                }
            }

            //Asset Variable
            $template_variables = AssetTemplateVariable::where('asset_template_id', $asset->asset_template_id)->get();
            foreach ($template_variables as $variable) 
            {
                $templateZone = TemplateZone::where('template_zone_id', $variable->template_zone_id)->first();
                if ($templateZone) 
                {
                    $assetZone = AssetZone::where('asset_id', $asset->asset_id)->where('zone_name', $templateZone->zone_name)->first();
                    if ($assetZone) 
                    {
                        $newVariable = AssetVariable::create([
                            'asset_id' => $asset->asset_id,
                            'area_id' => $asset->area_id,
                            'plant_id' => $asset->plant_id,
                            'asset_zone_id' => $assetZone->asset_zone_id,
                            'variable_id' => $variable->variable_id,
                            'variable_type_id' => $variable->variable_type_id
                        ]);

                        foreach ($variable->TemplateVariableValue as $variable_value) 
                        {
                            AssetVariableValue::create([
                                'asset_variable_id' => $newVariable->asset_variable_id,
                                'asset_id' => $asset->asset_id,
                                'asset_zone_id' => $assetZone->asset_zone_id,
                                'variable_id' => $variable_value->variable_id,
                                'variable_attribute_id' => $variable_value->variable_attribute_id,
                                'field_value' => $variable_value->field_value
                            ]);
                        }
                    }
                }
            }

            //Asset DataSources
            $template_datasources = AssetTemplateDataSource::where('asset_template_id', $asset->asset_template_id)->get();
            foreach ($template_datasources as $source) 
            {
                $templateZone = TemplateZone::where('template_zone_id', $source->template_zone_id)->first();
                if ($templateZone) 
                {
                    $assetZone = AssetZone::where('asset_id', $asset->asset_id)->where('zone_name', $templateZone->zone_name)->first();
                    if ($assetZone) 
                    {
                        $newSource = AssetDataSource::create([
                            'asset_id' => $asset->asset_id,
                            'area_id' => $asset->area_id,
                            'plant_id' => $asset->plant_id,
                            'asset_zone_id' => $assetZone->asset_zone_id,
                            'data_source_id' => $source->data_source_id,
                            'script' => $source->script,
                            'data_source_type_id' => $source->data_source_type_id
                        ]);

                        foreach ($source->TemplateDataSourceValue as $source_value) 
                        {
                            AssetDataSourceValue::create([
                                'asset_data_source_id' => $newSource->asset_data_source_id,
                                'asset_id' => $asset->asset_id,
                                'asset_zone_id' => $assetZone->asset_zone_id,
                                'data_source_id' => $source_value->data_source_id,
                                'data_source_attribute_id' => $source_value->data_source_attribute_id,
                                'field_value' => $source_value->field_value
                            ]);
                        }
                    }
                }
            }

            //Asset Accessories
            $template_accessoriess = AssetTemplateAccessory::where('asset_template_id', $asset->asset_template_id)->get();
            foreach ($template_accessoriess as $access) 
            {
                $templateZone = TemplateZone::where('template_zone_id', $access->template_zone_id)->first();
                if ($templateZone) 
                {
                    $assetZone = AssetZone::where('asset_id', $asset->asset_id)->where('zone_name', $templateZone->zone_name)->first();
                    if ($assetZone) 
                    {
                        $newAccess = AssetAccessory::create([
                            'asset_id' => $asset->asset_id,
                            'area_id' => $asset->area_id,
                            'plant_id' => $asset->plant_id,
                            'asset_zone_id' => $assetZone->asset_zone_id,
                            'accessory_type_id' => $access->accessory_type_id,
                            'accessory_name' => $access->accessory_name,
                            'attachment' => $access->attachment
                        ]);
                    }
                }
            }
        }        

        return response()->json(["message" => "Asset Created Successfully"], 200);
    }


    public function getAssets()
    {
        // $userPlantId = Auth::User()->plant_id;
        // $asset = Asset::where('plant_id', $userPlantId)->get();
        $asset = Asset::all();
        return AssetResource::collection($asset);
    }

    public function getAsset(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id'
        ]);

        $asset = Asset::where('asset_id', $request->asset_id)->first();

        //QR Code
        $assetCode = $asset->asset_code;

        $qrCode = new DNS2D();
        $qrCodeData = $qrCode->getBarcodePNG($assetCode, 'QRCODE', 10, 10);
    
        $dataUri = 'data:image/jpeg;base64,' . $qrCodeData;

        return response()->json([
            'asset' => new AssetResource($asset),
            'QRCode' => $dataUri,
            'asset_code' => $assetCode
        ]);
    }

    public function getAssetdata(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id'
        ]);

        $asset = Asset::where('asset_id', $request->asset_id)->first();
        return new AssetResource($asset);
    }

    public function getAssetCode(Request $request)
    {
        $request->validate([
            'asset_code' => 'required'
        ]);

        $asset = Asset::where('asset_code',$request->asset_code)->first();
        if($asset)
        {
            return new AssetResource($asset);
        }
        else {
            return response()->json([
                "message" => "Please Scan Valid QR Code"
            ],422);
        }
    }

    public function updateAsset(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_code' => 'required|string|unique:assets,asset_code,' . $request->asset_id . ',asset_id',
            'asset_name' => 'required|string|unique:assets,asset_name,' . $request->asset_id . ',asset_id',
            'no_of_zones' => 'required|integer',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_attributes' => 'nullable|array',
            'asset_attributes.*.asset_attribute_id' => 'nullable|exists:asset_attributes,asset_attribute_id',
            'asset_attributes.*.asset_attribute_value.field_value' => 'nullable',
            'longitude' => 'nullable|sometimes|numeric',
            'latitude' => 'nullable|sometimes|numeric',
            'functional_id' => 'nullable|exists:functionals,functional_id',
            'section_id' => 'nullable|exists:sections,section_id',
            'radius' => 'nullable|sometimes|numeric',
            'zone_name' => 'nullable|array',
            'deleted_asset_attribute_values' => 'nullable',
            'plant_id' => 'required|exists:plants,plant_id' ,
            'area_id' => 'nullable|exists:areas,area_id',
            'geometry_type' => 'nullable',
            'height' => 'nullable|required_if:geometry_type,V-Cylindrical,H-Cylindrical',
            'diameter' => 'nullable|required_if:geometry_type,V-Cylindrical,H-Cylindrical'
        ]);

        $request->validate([
            'zone_name' => function ($attribute, $value, $fail) use ($request) {
                if (isset($value)) {
                    $totalHeight = 0;
                    $assetDiameter = (float) $request->input('diameter', 0); 
                    $assetHeight = (float) $request->input('height', 0);
                    
                    foreach ($value as $zone) {
                        $zoneHeight = isset($zone['height']) ? (float) $zone['height'] : 0;
                        $zoneDiameter = isset($zone['diameter']) ? (float) $zone['diameter'] : 0;
                        $zoneName = $zone['zone_name'] ?? '';
        
                        if ($zoneName !== 'Overall') {
                                $totalHeight += $zoneHeight;
                            } else { 
                                $overallZoneHeight = $zoneHeight;
                            }
        
                        if ($zoneDiameter != $assetDiameter) {
                            $fail("The diameter of each AssetZones must be equal to the Asset's diameter.");
                            return; 
                        }
                    }
        
                    if ($totalHeight != $assetHeight) {
                        $fail("The total height of AssetZones (excluding Overall) must be equal to the Asset's height.");
                    }
                
                    if ($overallZoneHeight !== $assetHeight) {
                        $fail("The height of the Overall zone must be equal to the Asset's height.");
                    }
                }
            }
        ]);          
    
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $asset->update($data);

        if(isset($request->asset_departments))
        {
            if(isset($request->deleted_asset_departments) > 0)
            {
                AssetDepartment::whereIn('asset_department_id', $request->deleted_asset_departments)->forceDelete();
            }

            foreach ($request->asset_departments as $department) 
            {
                $assetdepartment = AssetDepartment::where('asset_id', $asset->asset_id)->where('department_id', $department)->first();
                if($assetdepartment)
                {
                    $assetdepartment->update([
                        'department_id' => $department,
                    ]);
                }
                else {
                    AssetDepartment::create([
                        'asset_id' => $asset->asset_id,
                        'department_id' => $department,
                    ]);
                }
            }
        }
        if($request->deleted_asset_attribute_values > 0)
        {
            AssetAttributeValue::whereIn('asset_attribute_value_id', $request->deleted_asset_attribute_values)->forceDelete();
        }
    
        foreach ($request->asset_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? $attribute['asset_attribute_value']['field_value'] ?? null;
    
            if ($fieldValue !== null) {
                AssetAttributeValue::updateOrCreate(
                    [
                        'asset_id' => $asset->asset_id,
                        'asset_attribute_id' => $attribute['asset_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
    
        $existingZones = AssetZone::where('asset_id', $asset->asset_id)->get();
        // $zoneNames = $request->zone_name;

        if(isset($request->deleted_asset_zones) > 0)
        {
            AssetZone::whereIn('asset_zone_id', $request->deleted_asset_zones)->forceDelete();
        }
    
        $zoneNames = array_filter($request->zone_name, function ($zone) {
            return $zone['zone_name'] !== 'Overall';
        });

        if (count($zoneNames) !== $data['no_of_zones']) {
            return response()->json(["error" => "The number of zone names must match the number of zones."], 400);
        }
    
        foreach ($zoneNames as $zoneName) 
        {

            $assetZone = AssetZone::where('asset_id', $asset->asset_id)
                              ->where('asset_zone_id', $zoneName['asset_zone_id'] ?? null)->first();
            if($assetZone)
            {   
                $assetZone ->update([
                    'zone_name' => $zoneName['zone_name'],
                    'height' => $zoneName['height'],
                    'diameter' => $zoneName['diameter']
                ]);
            }
            else {
                AssetZone::create([
                    'asset_id' => $asset->asset_id,
                    'zone_name' => $zoneName['zone_name'],
                    'height' => $zoneName['height'] ?? null,
                    'diameter' => $zoneName['diameter'] ?? null
                ]);
            }
        }
    
        return response()->json(["message" => "Asset Updated Successfully"]);
    }    

    public function deleteAsset(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id'
        ]);
        $asset = Asset::withTrashed()->where('asset_id', $request->asset_id)->first();
    
        if($asset->trashed())
        {
            $asset->restore();
            return response()->json([
                "message" =>"Asset Activated Successfully"
            ],200);
        }
        else
        {
            $asset->delete();
            return response()->json([
                "message" =>"Asset Deactivated Successfully"
            ], 200); 
        }
    }

    public function forceDeleteAsset(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id'
        ]);

        $isReferenced = BreakDownList::where('asset_id', $request->asset_id)->exists();
        $campaign = Campaign::where('asset_id', $request->asset_id)->exists();
        $activity = UserActivity::where('asset_id', $request->asset_id)->exists();
        $service = UserService::where('asset_id', $request->asset_id)->exists();
        $check = UserCheck::where('asset_id', $request->asset_id)->exists();
        $variable = UserVariable::where('asset_id', $request->asset_id)->exists();
        if ($isReferenced || $campaign || $activity || $service || $check || $variable) 
        {
            return response()->json([
                "message" => 'Asset cannot be deleted as it is used in other records.'
            ], 400);
        }

        AssetSpareValue::where('asset_id', $request->asset_id)->forceDelete();
        AssetSpare::where('asset_id', $request->asset_id)->forceDelete();
        AssetCheck::where('asset_id', $request->asset_id)->forceDelete();
        AssetServiceValue::where('asset_id', $request->asset_id)->forceDelete();
        AssetService::where('asset_id', $request->asset_id)->forceDelete();
        AssetVariableValue::where('asset_id', $request->asset_id)->forceDelete();
        AssetVariable::where('asset_id', $request->asset_id)->forceDelete();
        AssetDataSourceValue::where('asset_id', $request->asset_id)->forceDelete();
        AssetDataSource::where('asset_id', $request->asset_id)->forceDelete();
        AssetAccessory::where('asset_id', $request->asset_id)->forceDelete();
        AssetZone::where('asset_id', $request->asset_id)->forceDelete();
        AssetDepartment::where('asset_id', $request->asset_id)->forceDelete();
        AssetAttributeValue::where('asset_id', $request->asset_id)->forceDelete();

        Asset::where('asset_id', $request->asset_id)->forceDelete();

        return response()->json([
            "message" => 'Asset Deleted Successfully'
        ]);
    }

    public function getAssetsDropdown(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $asset_type = AssetAttribute::whereHas('AssetAttributeTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();

        return AssetAttributeResource::collection($asset_type);
    } 

    public function getAssetQRCode(Request $request)
    {
        $request->validate([
            'asset_code' => 'required'
        ]);

        $assetCode = $request->input('asset_code');

        $qrCode = new DNS2D();
        $qrCodeData = $qrCode->getBarcodePNG($assetCode, 'QRCODE', 10, 10);
    
        $dataUri = 'data:image/jpeg;base64,' . $qrCodeData;

        return response()->json([
            "QRCode" => $dataUri,
            'asset_code' => $assetCode
        ]);
    }

    public function downloadAssetQRCode(Request $request)
    {
        $request->validate([
            'asset_code' => 'required'
        ]);

        $assetCode = $request->input('asset_code');

        $qrCode = new DNS2D();
        $qrCodeData = $qrCode->getBarcodePNG($assetCode, 'QRCODE', 15, 15);

        $dataUri = 'data:image/jpeg;base64,' . $qrCodeData;

        $data = [
            'data_uri' => $dataUri,
            'asset_code' => $assetCode
        ];

        $pdf = PDF::loadView('QRCode', $data);
        return $pdf->stream('QRCode.pdf');
    }

    public function getAssetZones(Request $request)
    {
        $zones = AssetZone::where('asset_id', $request->asset_id)->get();
        return AssetZoneResource::collection($zones);
    }
}
