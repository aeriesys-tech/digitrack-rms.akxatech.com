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

class AssetController extends Controller
{
    public function paginateAssets(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $authPlantId = Auth::User()->plant_id;
        $query = Asset::query();

        $query->where('plant_id', $authPlantId);

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
                ->orWhere('asset_name', 'like', "$request->search%")
                ->orWhereHas('AssetType', function ($q) use ($request) {
                    $q->where('asset_type_code', 'like', "$request->search%")
                    ->orWhere('asset_type_name', 'like', "$request->search%");
                });
        }
        $asset = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AssetResource::collection($asset);
    }

    public function addAsset(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $areaId = Auth::User()->Plant->area_id;
        $data = $request->validate([
            // 'area_id' => 'required|exists:areas,area_id',
            'asset_code' => 'required|string|unique:assets,asset_code',
            'asset_name' => 'required|string|unique:assets,asset_name',
            'no_of_zones' => 'required|integer',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_attributes' => 'required|array',
            'asset_attributes.*.asset_attribute_id' => 'required|exists:asset_attributes,asset_attribute_id',
            'asset_attributes.*.field_values' => 'required|string',
            'longitude' => 'nullable|sometimes',
            'latitude' => 'nullable|sometimes',
            'functional_id' => 'nullable|exists:functionals,functional_id',
            'department_id' => 'nullable|exists:departments,department_id',
            'section_id' => 'nullable|exists:sections,section_id',
            'radius' => 'nullable|sometimes'
        ]);
        $data['plant_id'] = $userPlantId;
        $data['area_id'] = $areaId;
        
        $asset = Asset::create($data);

        // $asset_attribute_initial = AssetAttribute::whereHas('AssetattributeTypes', function($que) use($request){
        //     $que->where('asset_type_id', $request->asset_type_id);
        // })->get();

        foreach ($request->asset_attributes as $attribute) 
        {
            AssetAttributeValue::create([
                'asset_id' => $asset->asset_id,
                'asset_attribute_id' => $attribute['asset_attribute_id'],
                'field_value' => $attribute['field_value'] ?? '',
            ]);
        }

        // $update_assets = AssetAttributeValue::where('asset_id',  $asset->asset_id)->get();

        // foreach ($update_assets as $update_asset) {
        //     foreach ($data['asset_attributes'] as $asset_attribute) {
        //         if ($asset_attribute['asset_attribute_id'] == $update_asset['asset_attribute_id']) {
        //             $update_asset->update([
        //                 'field_value' => $asset_attribute['field_value'] ?? '',
        //             ]);
        //         }
        //     }
        // }  
        
        $n0_of_zones = $request->no_of_zones;
        for ($i = 1; $i <= $n0_of_zones; $i++) 
        {
            // $zoneName = "Zone {$i}"; 
            AssetZone::create([
                'asset_id' => $asset->asset_id,
                'zone_name' => $request->zone_name,
            ]);
        }
        
        return response()->json(["message" => "Asset Created Successfully"]);
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
        return new AssetResource($asset);
    }

    public function getAssetdata(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id'
        ]);

        $asset_attribute_value = AssetAttributeValue::where('asset_id', $request->asset_id)->get('asset_attribute_id');
        
        $asset_attribute_initial = AssetAttribute::whereNotIn('asset_attribute_id', $asset_attribute_value)->get();

        foreach ($asset_attribute_initial as $attribute) {
            AssetAttributeValue::create([
                'asset_id' => $request->asset_id,
                'asset_attribute_id' => $attribute['asset_attribute_id'],
                'field_value' => $attribute['field_value'] ?? '',
            ]);
        }

        $asset = Asset::where('asset_id', $request->asset_id)->first();
        return new AssetResource($asset);
    }

    public function getAssetCode(Request $request)
    {
        $request->validate([
            'asset_code' => 'required'
        ]);

        $asset = Asset::where('asset_code',$request->asset_code)->first();
        return new AssetResource($asset);
    }

    public function updateAsset(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'area_id' => 'required|exists:areas,area_id',
            'asset_code' => 'required|string|unique:assets,asset_code',
            'asset_name' => 'required|string|unique:assets,asset_name',
            'no_of_zones' => 'required|integer',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_attributes' => 'required|array',
            'asset_attributes.*.asset_attribute_id' => 'required|exists:asset_attributes,asset_attribute_id',
            'asset_attributes.*.field_value' => 'required|string',
            'longitude' => 'nullable|sometimes',
            'latitude' => 'nullable|sometimes',
            'functional_id' => 'nullable|exists:functionals,functional_id',
            'department_id' => 'nullable|exists:departments,department_id',
            'section_id' => 'nullable|exists:sections,section_id',
            'radius' => 'nullable|sometimes'
        ]);
    
        $data['plant_id'] = $userPlantId;
    
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $asset->update($data);
    
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

        $new_zone_count = $data['no_of_zones'];
        $existing_zones = AssetZone::where('asset_id', $asset->asset_id)->get();

        if ($existing_zones->count() > $new_zone_count) {
            $excess_zones = $existing_zones->slice($new_zone_count);
            foreach ($excess_zones as $zone) {
                $zone->delete();
            }
        }

        if ($existing_zones->count() < $new_zone_count) {
            $existing_zone_names = $existing_zones->pluck('zone_name')->toArray();
            for ($i = $existing_zones->count(); $i < $new_zone_count; $i++) {
                $zone_name = "Zone " . ($i + 1);
                if (!in_array($zone_name, $existing_zone_names)) {
                    AssetZone::create([
                        'asset_id' => $asset->asset_id,
                        'zone_name' => $zone_name,
                    ]);
                }
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
        $campaign = CampaignResult::withTrashed()->where('asset_id', $request->asset_id)->first();

        if($asset->trashed() && $campaign->trashed())
        {
            $asset->restore();
            $campaign->restore();
            return response()->json([
                "message" =>"Asset Activated successfully"
            ],200);
        }
        else
        {
            $asset->delete();
            $campaign->delete();
            return response()->json([
                "message" =>"Asset Deactivated successfully"
            ], 200); 
        }
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
}
