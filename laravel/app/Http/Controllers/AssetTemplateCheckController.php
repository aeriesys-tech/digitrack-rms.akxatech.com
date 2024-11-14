<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetTemplateCheck;
use App\Models\Check;
use App\Models\TemplateZone;
use App\Models\AssetTemplate;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CheckResource;
use App\Http\Resources\AssetTemplateCheckResource;

class AssetTemplateCheckController extends Controller
{
    public function paginateAssetTemplateChecks(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'department_id' => 'nullable|exists:departments,department_id'
        ]);

        $query = AssetTemplateCheck::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->check_id))
        {
            $query->where('check_id',$request->check_id);
        }
        if(isset($request->asset_template_id))
        {
            $query->where('asset_template_id',$request->asset_template_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orwherehas('Check', function($query) use($request){
                $query->where('check_name', 'like', "$request->search%");
            })->orwherehas('AssetTemplate', function($query) use($request){
                $query->where('template_name', 'like', "$request->search%");
            });
        }
        $template_check = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 

        $checks = Check::whereHas('CheckAssetTypes', function($que) use ($request) {
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();

        return response()->json([
            'paginate_checks' => AssetTemplateCheckResource::collection($template_check),
            'meta' => [
                'current_page' => $template_check->currentPage(),
                'last_page' => $template_check->lastPage(),
                'per_page' => $template_check->perPage(),
                'total' => $template_check->total(),
            ],
            'checks' => CheckResource::collection($checks)
        ]);
    }

    public function addAssetTemplateCheck(Request $request)
    {
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $checkdata = Check::where('check_id', $request->check_id)->first();
        $data = $request->validate([
            'check_id' => [
                'required',
                'exists:checks,check_id',
                function ($attribute, $value, $fail) use ($request, $assetHasZones) {
                    $exists = AssetTemplateCheck::where('check_id', $value)
                        ->where('asset_template_id', $request->asset_template_id)
                        ->where(function ($query) use ($request, $assetHasZones) {
                            if ($assetHasZones && $request->filled('check_template_zones')) {
                                $query->whereIn('template_zone_id', $request->check_template_zones);
                            } else {
                                $query->whereNull('template_zone_id');
                            }
                        })->exists();

                    if ($exists) {
                        if ($request->filled('check_template_zones') && $assetHasZones) {
                            $fail('The combination of Check and Asset Template Zone already exists.');
                        } else {
                            $fail('The combination of Check and Asset Template already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'check_template_zones' => [
                $assetHasZones ? 'required' : 'nullable', 
                'array',
            ],
            'template_zones.*' => 'nullable|exists:template_zones,template_zone_id',
            'lcl' => 'nullable|required_if:field_type,Number',
            'ucl' => 'nullable|required_if:field_type,Number',
            'default_value' => [
                'nullable',
                'required_if:field_type,Select',
                function ($attribute, $value, $fail) use ($request, $checkdata) {
                    if ($request->field_type === 'Select' && !empty($checkdata->field_values)) {
                        $fieldValuesArray = array_map('strtolower', array_map('trim', explode(',', $checkdata->field_values)));
                        if (!in_array(strtolower($value), $fieldValuesArray)) {
                            $formattedOptions = implode(', ', array_map('ucwords', $fieldValuesArray));
                            $fail("The default value must be one of the option in field values: " . $formattedOptions);
                        }
                    }
                }
            ],
        ]);

        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();

        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        // $check = Check::where('check_id', $request->check_id)->first();

        // $data['lcl'] = $request->input('lcl', $check->lcl);
        // $data['ucl'] = $request->input('ucl', $check->ucl);
        // $data['default_value'] = $request->input('default_value', $check->default_value);

        $createdChecks = [];

        if (!empty($data['check_template_zones'])) 
        {
            foreach ($data['check_template_zones'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $checksData = $data;
                $checksData['template_zone_id'] = $zoneId;

                $assetChecks = AssetTemplateCheck::create($checksData);
                $createdChecks[] = new AssetTemplateCheckResource($assetChecks);
            }
        } 
        else 
        {
            $checksData = $data;
            $checksData['template_zone_id'] = null;

            $assetChecks = AssetTemplateCheck::create($checksData);
            $createdChecks[] = new AssetTemplateCheckResource($assetChecks);
        }
        return response()->json([$createdChecks, "message" => "Template Check Created Successfully"]);
    }

    public function getAssetTemplateCheck(Request $request)
    {
        $request->validate([
            'asset_template_check_id' => 'required|exists:asset_template_checks,asset_template_check_id'
        ]);

        $asset_check = AssetTemplateCheck::where('asset_template_check_id', $request->asset_template_check_id)->first();
        return new AssetTemplateCheckResource($asset_check);
    }

    public function getAssetTemplateChecks(Request $request)
    {
        $request->validate([
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id'
        ]);
        $asset_check = AssetTemplateCheck::where('asset_template_id', $request->asset_template_id)->get();
        return AssetTemplateCheckResource::collection($asset_check);
    }

    public function updateAssetTemplateCheck(Request $request)
    {
        $asset_check = AssetTemplateCheck::where('asset_template_check_id', $request->asset_template_check_id)->first();
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $checkdata = Check::where('check_id', $request->check_id)->first();
        $data = $request->validate([
            'asset_template_check_id' => 'required|exists:asset_template_checks,asset_template_check_id',
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'check_id' => [
                'required',
                'exists:checks,check_id',
                function ($attribute, $value, $fail) use ($request, $asset_check) 
                {
                    if ($value != $asset_check->check_id) {
                        $exists = AssetTemplateCheck::where('check_id', $value)
                            ->where('asset_template_id', $request->asset_template_id)
                            ->where(function ($query) use ($request) {
                                if ($request->filled('template_zone_id')) {
                                    $query->where('template_zone_id', $request->template_zone_id);
                                } else {
                                    $query->whereNull('template_zone_id');
                                }
                            })->where('asset_template_check_id', '!=', $request->asset_template_check_id)->exists();
    
                        if ($exists) {
                            $fail('The combination of Check, Asset Template, and Template Zone already exists.');
                        }
                    }
                },
            ],
            'lcl' => 'nullable|required_if:field_type,Number',
            'ucl' => 'nullable|required_if:field_type,Number',
            'default_value' => [
                'nullable',
                'required_if:field_type,Select',
                function ($attribute, $value, $fail) use ($request, $checkdata) {
                    if ($request->field_type === 'Select' && !empty($checkdata->field_values)) {
                        $fieldValuesArray = array_map('strtolower', array_map('trim', explode(',', $checkdata->field_values)));
                        if (!in_array(strtolower($value), $fieldValuesArray)) {
                            $formattedOptions = implode(', ', array_map('ucwords', $fieldValuesArray));
                            $fail("The default value must be one of the option in field values: " . $formattedOptions);
                        }
                    }
                }
            ],
            'template_zone_id' => [
                $assetHasZones ? 'required' : 'nullable',
            ],
        ]);

        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();

        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        $asset_check = AssetTemplateCheck::where('asset_template_check_id', $request->asset_template_check_id)->first();
        $asset_check->update($data);
        return response()->json([
            "message" => "Template Check Updated Successfully",
            new AssetTemplateCheckResource($asset_check)
        ]);    
    }

    public function forceDeleteAssetTemplateCheck(Request $request)
    {
        $request->validate([
            'asset_template_check_id' => 'required|exists:asset_template_checks,asset_template_check_id'
        ]);

        $asset_check = AssetTemplateCheck::where('asset_template_check_id', $request->asset_template_check_id)->forceDelete();

        return response()->json([
            "message" => "Template Check deleted successfully"
        ], 200);
    }
}
