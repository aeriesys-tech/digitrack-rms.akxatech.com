<?php

namespace App\Http\Controllers;
use App\Models\AssetTemplateSpare;
use App\Models\Spare;
use App\Http\Resources\AssetTemplateSpareResource;
use App\Http\Resources\SpareResource;
use App\Models\TemplateZone;
use App\Models\AssetTemplate;
use App\Models\TemplateSpareValue;
use Illuminate\Http\Request;

class AssetTemplateSpareController extends Controller
{
    public function paginateAssetTemplateSpares(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
        ]);

        $query = AssetTemplateSpare::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->spare_id))
        {
            $query->where('spare_id',$request->spare_id);
        }
        if(isset($request->asset_template_id))
        {
            $query->where('asset_template_id',$request->asset_template_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orWherehas('AssetTemplate', function($query) use($request){
                $query->where('template_name', 'like', "$request->search%");
            })->orWherehas('Spare', function($query) use($request){
                $query->where('spare_name', 'like', "$request->search%");
            });
        }
        $template_spare = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 

        //AssetSpare DropDown
        $spares = Spare::whereHas('SpareAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
    
        return response()->json([
            'paginate_spares' => AssetTemplateSpareResource::collection($template_spare),
            'meta' => [
                'current_page' => $template_spare->currentPage(),
                'last_page' => $template_spare->lastPage(),
                'per_page' => $template_spare->perPage(),
                'total' => $template_spare->total(),
            ],
            'spares' => SpareResource::collection($spares),
        ]);
    }

    public function addAssetTemplateSpare(Request $request)
    {
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $data = $request->validate([
           'spare_id' => [
                'required',
                'exists:spares,spare_id',
                function ($attribute, $value, $fail) use ($request, $assetHasZones) {
                    $exists = AssetTemplateSpare::where('spare_id', $value)
                        ->where('asset_template_id', $request->asset_template_id)
                        ->where(function ($query) use ($request, $assetHasZones) {
                            if ($assetHasZones && $request->filled('spare_template_zones')) {
                                $query->whereIn('template_zone_id', $request->spare_template_zones);
                            } else {
                                $query->whereNull('template_zone_id');
                            }
                        })->exists();

                    if ($exists) {
                        if ($request->filled('spare_template_zones') && $assetHasZones) {
                            $fail('The combination of Spare, Asset Template, and Template Zone already exists.');
                        } else {
                            $fail('The combination of Spare and Asset Template already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'spare_template_zones' => [
                $assetHasZones ? 'required' : 'nullable', 
                'array',
            ],
            'template_zones.*' => 'nullable|exists:template_zones,template_zone_id',
            'quantity' =>  'required|min:0'
        ]);

        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        $spare = Spare::where('spare_id', $request->spare_id)->first();

        $data['spare_type_id'] = $spare->spare_type_id;
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        $createdSpares = [];

        if (!empty($data['spare_template_zones'])) 
        {
            foreach ($data['spare_template_zones'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $spareData = $data;
                $spareData['template_zone_id'] = $zoneId;

                $templateSpare = AssetTemplateSpare::create($spareData);
                $createdSpares[] = new AssetTemplateSpareResource($templateSpare);

                foreach($request->asset_spare_attributes as $attribute)
                {
                    TemplateSpareValue::create([
                        'asset_template_spare_id' => $templateSpare->asset_template_spare_id,
                        'asset_template_id' => $templateSpare->asset_template_id,
                        'spare_id' => $templateSpare->spare_id,
                        'template_zone_id' => $templateSpare->template_zone_id,
                        'spare_attribute_id' => $attribute['spare_attribute_id'],
                        'field_value' => $attribute['field_value'] ?? ''
                    ]);
                }
            }
        } 
        else 
        {
            $spareData = $data;
            $spareData['template_zone_id'] = null;

            $templateSpare = AssetTemplateSpare::create($spareData);
            $createdSpares[] = new AssetTemplateSpareResource($templateSpare);

            foreach($request->asset_spare_attributes as $attribute)
            {
                TemplateSpareValue::create([
                    'asset_template_spare_id' => $templateSpare->asset_template_spare_id,
                    'asset_template_id' => $templateSpare->asset_template_id,
                    'spare_id' => $templateSpare->spare_id,
                    'template_zone_id' => $templateSpare->template_zone_id,
                    'spare_attribute_id' => $attribute['spare_attribute_id'],
                    'field_value' => $attribute['field_value'] ?? ''
                ]);
            }
        }
        return response()->json([$createdSpares, 201,  "message" => "Template Spare Created Successfully"]);
    }

    public function getAssetTemplateSpare(Request $request)
    {
        $request->validate([
            'asset_template_spare_id' => 'required|exists:asset_template_spares,asset_template_spare_id'
        ]);

        $template_spare = AssetTemplateSpare::where('asset_template_spare_id',$request->asset_template_spare_id)->first();
        return new AssetTemplateSpareResource($template_spare);
    }

    public function getAssetTemplateSpares()
    {
        $template_spare = AssetTemplateSpare::all();
        return AssetTemplateSpareResource::collection($template_spare);
    }


    public function updateAssetTemplateSpare(Request $request)
    {
        // $userPlantId = Auth::User()->plant_id;
        // $areaId = Auth::User()->Plant->area_id;

        $asset_spare = AssetTemplateSpare::where('asset_template_spare_id', $request->asset_template_spare_id)->first();
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $data = $request->validate([
            'asset_template_spare_id' => 'required|exists:asset_template_spares,asset_template_spare_id',
            'spare_id' => [
                'required',
                'exists:spares,spare_id',
                function ($attribute, $value, $fail) use ($request, $asset_spare) 
                {
                    if ($value != $asset_spare->spare_id) {
                        $exists = AssetTemplateSpare::where('spare_id', $value)
                            ->where('asset_template_id', $request->asset_template_id)
                            ->where(function ($query) use ($request) {
                                if ($request->filled('template_zone_id')) {
                                    $query->where('template_zone_id', $request->template_zone_id);
                                } else {
                                    $query->whereNull('template_zone_id');
                                }
                            })->where('template_zone_id', '!=', $request->template_zone_id)->exists();
    
                        if ($exists) {
                            $fail('The combination of Spare, Asset Template, and Template Zone already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'template_zone_id' => [
                $assetHasZones ? 'required' : 'nullable',
            ],
            'quantity' =>  'required|min:0'
        ]);

        $spare = Spare::where('spare_id', $request->spare_id)->first();
        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['spare_type_id'] = $spare->spare_type_id;

        $asset_spare = AssetTemplateSpare::where('asset_template_spare_id', $request->asset_template_spare_id)->first();
        $asset_spare->update($data);

        if(isset($request->deleted_asset_spare_values)>0)
        {
            TemplateSpareValue::whereIn('template_spare_value_id', $request->deleted_asset_spare_values)->forceDelete();
        }

        foreach ($request->asset_spare_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? '';

            if ($fieldValue !== null) {
                TemplateSpareValue::updateOrCreate(
                    [
                        'asset_template_spare_id' => $asset_spare->asset_template_spare_id,
                        'template_zone_id' => $asset_spare->template_zone_id,
                        'spare_id' => $spare->spare_id,
                        'asset_template_id' =>  $asset_spare->asset_template_id,
                        'spare_attribute_id' => $attribute['spare_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }

        return response()->json([
            "message" => "Template Spare Updated Successfully",
            new AssetTemplateSpareResource($asset_spare)
        ]); 
    }

    public function forceDeleteAssetTemplateSpare(Request $request)
    {
        $request->validate([
            'asset_template_spare_id' => 'required|exists:asset_template_spares,asset_template_spare_id'
        ]);
    
        TemplateSpareValue::where('asset_template_spare_id', $request->asset_template_spare_id)->forceDelete();
        $asset_spare = AssetTemplateSpare::where('asset_template_spare_id', $request->asset_template_spare_id)->forceDelete();

        return response()->json([
            "message" => "Template Spare deleted successfully"
        ], 200);
    } 
}
