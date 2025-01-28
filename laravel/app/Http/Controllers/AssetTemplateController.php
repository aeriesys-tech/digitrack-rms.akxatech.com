<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Resources\AssetTemplateResource;
use App\Models\AssetTemplate;
use App\Models\TemplateDepartment;
use App\Models\TemplateAttributeValue;
use App\Models\TemplateZone;
use App\Models\AssetAttribute;
use App\Models\AssetTemplateAccessory;
use App\Models\TemplateDataSourceValue;
use App\Models\AssetTemplateDataSource;
use App\Models\TemplateVariableValue;
use App\Models\AssetTemplateVariable;
use App\Models\TemplateServiceValue;
use App\Models\AssetTemplateService;
use App\Models\AssetTemplateCheck;
use App\Models\TemplateSpareValue;
use App\Models\AssetTemplateSpare;
use App\Http\Resources\AssetAttributeResource;
use App\Http\Resources\TemplateZoneResource;
use App\Http\Resources\DuplicateAssetTemplateResource;

class AssetTemplateController extends Controller
{
    public function paginateAssetTemplates(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        // // $authPlantId = Auth::User()->plant_id;
        $query = AssetTemplate::query();

        // // $query->where('plant_id', $authPlantId);

        if(isset($request->asset_type_id))
        {
            $query->where('asset_type_id',$request->asset_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('template_code', 'like', "%$request->search%")
                ->orWhere('template_name', 'like', "%$request->search%")
                ->orWhereHas('AssetType', function ($q) use ($request) {
                    $q->where('asset_type_code', 'like', "%$request->search%")
                    ->orWhere('asset_type_name', 'like', "%$request->search%");
                });
        }
        if ($request->keyword == 'asset_type_name') {
            $query->join('asset_type', 'asset_templates.asset_type_id', '=', 'asset_type.asset_type_id')->select('asset_templates.*') 
                  ->orderBy('asset_type.asset_type_name', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }
        $templates = $query->withTrashed()->paginate($request->per_page); 
        return AssetTemplateResource::collection($templates);
    }

    public function addAssetTemplate(Request $request)
    {
        $data = $request->validate([
            'template_code' => 'required|unique:asset_templates,template_code',
            'template_name' => 'required|unique:asset_templates,template_name',
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
            'diameter' => 'nullable|required_if:geometry_type,V-Cylindrical,H-Cylindrical'
        ]);
     
        $request->validate([
            'zone_name' => function ($attribute, $value, $fail) use ($request) {
                if (isset($value)) {
                    $totalHeight = 0;
                    $assetDiameter = $request->input('diameter', 0); 
        
                    foreach ($value as $zone) {
                        $zoneHeight = $zone['height'] ?? 0;
                        $zoneDiameter = $zone['diameter'] ?? 0;
        
                        $totalHeight += $zoneHeight;
        
                        if ($zoneDiameter != $assetDiameter) {
                            $fail("The diameter of each TemplateZones must be equal to the AssetTemplate's diameter.");
                            return; 
                        }
                    }
        
                    $assetHeight = $request->input('height', 0);
                    if ($totalHeight != $assetHeight) {
                        $fail("The total height of TemplateZones must be equal to the AssetTemplate's height.");
                    }
                }
            }
        ]);       

        $template = AssetTemplate::create($data);

        if (isset($request->asset_departments)) 
        {
            foreach ($request->asset_departments as $department) {
                TemplateDepartment::updateOrCreate([
                    'asset_template_id' => $template->asset_template_id,
                    'department_id' => $department,
                ]);
            }
        }

        if (isset($request->asset_attributes)) {
            foreach ($request->asset_attributes as $attribute) {
                TemplateAttributeValue::updateOrCreate(
                    [
                        'asset_template_id' => $template->asset_template_id,
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
            TemplateZone::updateOrCreate(
                [
                    'asset_template_id' => $template->asset_template_id,
                    'zone_name' => $zoneName['zone_name']
                ],
                [
                    'height' => $zoneName['height'] ?? null,
                    'diameter' => $zoneName['diameter'] ?? null
                ]
            );
        }

        return response()->json(["message" => "AssetTemplate created successfully"], 200);
    }

    public function getAssetTemplates(Request $request)
    {
        // $userPlantId = Auth::User()->plant_id;
        // $asset = Asset::where('plant_id', $userPlantId)->get();
        $templates = AssetTemplate::all();
        return AssetTemplateResource::collection($templates);
    }

    public function getAssetTemplate(Request $request)
    {
        $request->validate([
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id'
        ]);

        $template = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        return new AssetTemplateResource($template);
    }

    public function updateAssetTemplate(Request $request)
    {
        $data = $request->validate([
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'template_code' => 'required|string|unique:asset_templates,template_code,' . $request->asset_template_id . ',asset_template_id',
            'template_name' => 'required|string|unique:asset_templates,template_name,' . $request->asset_template_id . ',asset_template_id',
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
                    $assetDiameter = $request->input('diameter', 0); 
        
                    foreach ($value as $zone) {
                        $zoneHeight = $zone['height'] ?? 0;
                        $zoneDiameter = $zone['diameter'] ?? 0;
        
                        $totalHeight += $zoneHeight;
        
                        if ($zoneDiameter != $assetDiameter) {
                            $fail("The diameter of each TemplateZones must be equal to the AssetTemplate's diameter.");
                            return; 
                        }
                    }
        
                    $assetHeight = $request->input('height', 0);
                    if ($totalHeight != $assetHeight) {
                        $fail("The total height of TemplateZone must be equal to the AssetTemplate's height.");
                    }
                }
            }
        ]);        
    
        $template = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        $template->update($data);

        if(isset($request->asset_departments))
        {
            if(isset($request->deleted_asset_departments) > 0)
            {
                TemplateDepartment::whereIn('template_department_id', $request->deleted_asset_departments)->forceDelete();
            }

            foreach ($request->asset_departments as $department) 
            {
                $templatedepartment = TemplateDepartment::where('asset_template_id', $template->asset_template_id)->where('department_id', $department)->first();
                if($templatedepartment)
                {
                    $templatedepartment->update([
                        'department_id' => $department,
                    ]);
                }
                else {
                    TemplateDepartment::create([
                        'asset_template_id' => $template->asset_template_id,
                        'department_id' => $department,
                    ]);
                }
            }
        }
        if($request->deleted_asset_attribute_values > 0)
        {
            TemplateAttributeValue::whereIn('template_attribute_value_id', $request->deleted_asset_attribute_values)->forceDelete();
        }
    
        foreach ($request->asset_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? $attribute['asset_attribute_value']['field_value'] ?? null;
    
            if ($fieldValue !== null) {
                TemplateAttributeValue::updateOrCreate(
                    [
                        'asset_template_id' => $template->asset_template_id,
                        'asset_attribute_id' => $attribute['asset_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
    
        $existingZones = TemplateZone::where('asset_template_id', $template->asset_template_id)->get();
        $zoneNames = $request->zone_name;

        if(isset($request->deleted_asset_zones) > 0)
        {
            TemplateZone::whereIn('template_zone_id', $request->deleted_asset_zones)->forceDelete();
        }
    
        if (count($zoneNames) !== $data['no_of_zones']) {
            return response()->json(["error" => "The number of zone names must match the number of zones."], 400);
        }
    
        foreach ($zoneNames as $zoneName) 
        {

            $templateZone = TemplateZone::where('asset_template_id', $template->asset_template_id)
                              ->where('template_zone_id', $zoneName['template_zone_id'] ?? null)->first();
            if($templateZone)
            {   
                $templateZone->update([
                    'zone_name' => $zoneName['zone_name'],
                    'height' => $zoneName['height'],
                    'diameter' => $zoneName['diameter']
                ]);
            }
            else {
                TemplateZone::create([
                    'asset_template_id' => $template->asset_template_id,
                    'zone_name' => $zoneName['zone_name'],
                    'height' => $zoneName['height'] ?? '',
                    'diameter' => $zoneName['diameter'] ?? ''
                ]);
            }
        }
    
        return response()->json(["message" => "AssetTemplate Updated Successfully"]);
    }    

    public function deleteAssetTemplate(Request $request)
    {
        $request->validate([
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id'
        ]);
        $template = AssetTemplate::withTrashed()->where('asset_template_id', $request->asset_template_id)->first();
    
        if($template->trashed())
        {
            $template->restore();
            return response()->json([
                "message" =>"Asset Template Activated Successfully"
            ],200);
        }
        else
        {
            $template->delete();
            return response()->json([
                "message" =>"Asset Template Deactivated Successfully"
            ], 200); 
        }
    }

    public function getTemplateZones(Request $request)
    {
        $templates = TemplateZone::where('asset_template_id', $request->asset_template_id)->get();
        return TemplateZoneResource::collection($templates);
    }

    public function forceDeleteAssetTemplate(Request $request)
    {
        $request->validate([
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id'
        ]);

        AssetTemplateAccessory::where('asset_template_id', $request->asset_template_id)->forceDelete();
        TemplateDataSourceValue::where('asset_template_id', $request->asset_template_id)->forceDelete();
        AssetTemplateDataSource::where('asset_template_id', $request->asset_template_id)->forceDelete();
        TemplateVariableValue::where('asset_template_id', $request->asset_template_id)->forceDelete();
        AssetTemplateVariable::where('asset_template_id', $request->asset_template_id)->forceDelete();
        TemplateServiceValue::where('asset_template_id', $request->asset_template_id)->forceDelete();
        AssetTemplateService::where('asset_template_id', $request->asset_template_id)->forceDelete();
        AssetTemplateCheck::where('asset_template_id', $request->asset_template_id)->forceDelete();
        TemplateSpareValue::where('asset_template_id', $request->asset_template_id)->forceDelete();
        AssetTemplateSpare::where('asset_template_id', $request->asset_template_id)->forceDelete();
        TemplateZone::where('asset_template_id', $request->asset_template_id)->forceDelete();
        TemplateDepartment::where('asset_template_id', $request->asset_template_id)->forceDelete();
        TemplateAttributeValue::where('asset_template_id', $request->asset_template_id)->forceDelete();
        AssetTemplate::where('asset_template_id', $request->asset_template_id)->forceDelete();

        return response()->json([
            "message" => "AssetTemplate Deleted Sucessfully"
        ]);
    }

    public function getAssetTemplateDropDown(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);
        $templates = AssetTemplate::where('asset_type_id', $request->asset_type_id)->get();
        return AssetTemplateResource::collection($templates);
    }

    public function getAssetTemplateToAsset(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id'
        ]);

        $template = AssetTemplate::where('asset_type_id', $request->asset_type_id)->where('asset_template_id', $request->asset_template_id)->first();
        return new DuplicateAssetTemplateResource($template);
    }
}
