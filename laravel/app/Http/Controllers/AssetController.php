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
        $data = $request->validate([
            'asset_code' => 'required|string|unique:assets,asset_code',
            'asset_name' => 'required|string|unique:assets,asset_name',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_attributes' => 'required|array',
            'asset_attributes.*.asset_attribute_id' => 'required|exists:asset_attributes,asset_attribute_id',
            'asset_attributes.*.field_value' => 'required|string',
            'longitude' => 'nullable|sometimes',
            'latitude' => 'nullable|sometimes',
            'department_id' => 'nullable|exists:departments,department_id',
            'section_id' => 'nullable|exists:sections,section_id',
            'radius' => 'nullable|sometimes'
        ]);
        $data['plant_id'] = $userPlantId;

        
        $asset = Asset::create($data);
        $asset_attribute_initial = AssetAttribute::whereHas('AssetattributeTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();

        foreach ($asset_attribute_initial as $attribute) {
            AssetAttributeValue::create([
                'asset_id' => $asset->asset_id,
                'asset_attribute_id' => $attribute['asset_attribute_id'],
                'field_value' => $attribute['field_value'] ?? '',
            ]);
        }

        $update_assets = AssetAttributeValue::where('asset_id',  $asset->asset_id)->get();

        foreach ($update_assets as $update_asset) {
            foreach ($data['asset_attributes'] as $asset_attribute) {
                if ($asset_attribute['asset_attribute_id'] == $update_asset['asset_attribute_id']) {
                    $update_asset->update([
                        'field_value' => $asset_attribute['field_value'] ?? '',
                    ]);
                }
            }
        }        
        
        return response()->json(["message" => "Asset Created Successfully"]);
    }

    public function getAssets()
    {
        $userPlantId = Auth::User()->plant_id;
    
        $asset = Asset::where('plant_id', $userPlantId)->get();
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
            'asset_code' => 'required|string|unique:assets,asset_code,' . $request->asset_id . ',asset_id',
            'asset_name' => 'required|string|unique:assets,asset_name,' . $request->asset_id . ',asset_id',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_attributes' => 'required|array',
            'asset_attributes.*.asset_attribute_id' => 'required|exists:asset_attributes,asset_attribute_id',
            'longitude' => 'nullable|sometimes',
            'latitude' => 'nullable|sometimes',
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
                "message" =>"Asset Activated successfully"
            ],200);
        }
        else
        {
            $asset->delete();
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
