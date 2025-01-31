<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetSpareResource;
use App\Models\AssetSpare;
use App\Models\Spare;
use App\Models\AssetZone;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SpareResource;
use App\Http\Resources\AssetZoneResource;
use App\Models\SpareAttributeValue;
use App\Http\Resources\SpareAttributeValueResource;
use App\Models\AssetSpareValue;
use App\Models\UserSpare;

class AssetSpareController extends Controller
{
    public function paginateAssetSpares(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
        ]);

        $query = AssetSpare::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->spare_id))
        {
            $query->where('spare_id',$request->spare_id);
        }
        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orWherehas('Asset', function($query) use($request){
                $query->where('asset_name', 'like', "$request->search%");
            })->orWherehas('Spare', function($query) use($request){
                $query->where('spare_name', 'like', "$request->search%");
            });
        }
        $asset_spare = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 

        //AssetSpare DropDown
        $spares = Spare::whereHas('SpareAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
    
        return response()->json([
            'paginate_spares' => AssetSpareResource::collection($asset_spare),
            'meta' => [
                'current_page' => $asset_spare->currentPage(),
                'last_page' => $asset_spare->lastPage(),
                'per_page' => $asset_spare->perPage(),
                'total' => $asset_spare->total(),
            ],
            'spares' => SpareResource::collection($spares),
        ]);
    }

    public function addAssetSpare(Request $request)
    {
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $data = $request->validate([
           'spare_id' => [
                'required',
                'exists:spares,spare_id',
                function ($attribute, $value, $fail) use ($request, $assetHasZones) {
                    $exists = AssetSpare::where('spare_id', $value)
                        ->where('asset_id', $request->asset_id)
                        ->where(function ($query) use ($request, $assetHasZones) {
                            if ($assetHasZones && $request->filled('spare_asset_zones')) {
                                $query->whereIn('asset_zone_id', $request->spare_asset_zones);
                            } else {
                                $query->whereNull('asset_zone_id');
                            }
                        })->exists();

                    if ($exists) {
                        if ($request->filled('spare_asset_zones') && $assetHasZones) {
                            $fail('The combination of Spare, Asset, and Asset Zone already exists.');
                        } else {
                            $fail('The combination of Spare and Asset already exists.');
                        }
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'spare_asset_zones' => [
                $assetHasZones ? 'required' : 'nullable', 
                'array',
            ],
            'asset_zones.*' => 'nullable|exists:asset_zones,asset_zone_id',
            'quantity' =>  'required|integer|min:1'
        ]);

        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $spare = Spare::where('spare_id', $request->spare_id)->first();

        $data['spare_type_id'] = $spare->spare_type_id;
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        $createdSpares = [];

        if (!empty($data['spare_asset_zones'])) 
        {
            foreach ($data['spare_asset_zones'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $spareData = $data;
                $spareData['asset_zone_id'] = $zoneId;

                $assetSpare = AssetSpare::create($spareData);
                $createdSpares[] = new AssetSpareResource($assetSpare);

                foreach($request->asset_spare_attributes as $attribute)
                {
                    AssetSpareValue::create([
                        'asset_spare_id' => $assetSpare->asset_spare_id,
                        'asset_id' => $assetSpare->asset_id,
                        'spare_id' => $assetSpare->spare_id,
                        'asset_zone_id' => $assetSpare->asset_zone_id,
                        'spare_attribute_id' => $attribute['spare_attribute_id'],
                        'field_value' => $attribute['field_value'] ?? ''
                    ]);
                }
            }
        } 
        else 
        {
            $spareData = $data;
            $spareData['asset_zone_id'] = null;

            $assetSpare = AssetSpare::create($spareData);
            $createdSpares[] = new AssetSpareResource($assetSpare);

            foreach($request->asset_spare_attributes as $attribute)
            {
                AssetSpareValue::create([
                    'asset_spare_id' => $assetSpare->asset_spare_id,
                    'asset_id' => $assetSpare->asset_id,
                    'spare_id' => $assetSpare->spare_id,
                    'asset_zone_id' => $assetSpare->asset_zone_id,
                    'spare_attribute_id' => $attribute['spare_attribute_id'],
                    'field_value' => $attribute['field_value'] ?? ''
                ]);
            }
        }
        return response()->json([$createdSpares, 201,  "message" => "AssetSpare Created Successfully"]);
    }

    public function getAssetSpare(Request $request)
    {
        $request->validate([
            'asset_spare_id' => 'required|exists:asset_spares,asset_spare_id'
        ]);

        $asset_spare = AssetSpare::where('asset_spare_id',$request->asset_spare_id)->first();
        return new AssetSpareResource($asset_spare);
    }

    public function getAssetSpares()
    {
        $asset_spare = AssetSpare::all();
        return AssetSpareResource::collection($asset_spare);
    }

    public function getAssetServiceSpares(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id'
        ]);

        $query = AssetSpare::where('asset_id', $request->asset_id);
        if ($request->has('asset_zone_id') && $request->asset_zone_id !== null) {
            $query->where('asset_zone_id', $request->asset_zone_id);
        }

        $spare_ids = $query->pluck('spare_id')->toArray();
        $asset_spare = Spare::whereIn('spare_id', $spare_ids)
            ->with(['AssetSpare' => function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id)->select('spare_id', 'quantity', 'asset_zone_id');
            }])
        ->get();

        return $asset_spare;
    }

    public function updateAssetSpare(Request $request)
    {
        // $userPlantId = Auth::User()->plant_id;
        // $areaId = Auth::User()->Plant->area_id;

        $asset_spare = AssetSpare::where('asset_spare_id', $request->asset_spare_id)->first();
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $data = $request->validate([
            'asset_spare_id' => 'required|exists:asset_spares,asset_spare_id',
            'spare_id' => [
                'required',
                'exists:spares,spare_id',
                function ($attribute, $value, $fail) use ($request, $asset_spare) 
                {
                    if ($value != $asset_spare->spare_id) {
                        $exists = AssetSpare::where('spare_id', $value)
                            ->where('asset_id', $request->asset_id)
                            ->where(function ($query) use ($request) {
                                if ($request->filled('asset_zone_id')) {
                                    $query->where('asset_zone_id', $request->asset_zone_id);
                                } else {
                                    $query->whereNull('asset_zone_id');
                                }
                            })->where('asset_spare_id', '!=', $request->asset_spare_id)->exists();
    
                        if ($exists) {
                            $fail('The combination of Spare, Asset, and Asset Zone already exists.');
                        }
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zone_id' => [
                $assetHasZones ? 'required' : 'nullable',
            ],
            'quantity' =>  'required|integer|min:1'
        ]);

        $spare = Spare::where('spare_id', $request->spare_id)->first();
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['spare_type_id'] = $spare->spare_type_id;

        $asset_spare = AssetSpare::where('asset_spare_id', $request->asset_spare_id)->first();
        $asset_spare->update($data);

        if(isset($request->deleted_asset_spare_values)>0)
        {
            AssetSpareValue::whereIn('asset_spare_value_id', $request->deleted_asset_spare_values)->forceDelete();
        }

        foreach ($request->asset_spare_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? '';

            if ($fieldValue !== null) {
                AssetSpareValue::updateOrCreate(
                    [
                        'asset_spare_id' => $asset_spare->asset_spare_id,
                        'asset_zone_id' => $asset_spare->asset_zone_id,
                        'spare_id' => $spare->spare_id,
                        'asset_id' =>  $asset_spare->asset_id,
                        'spare_attribute_id' => $attribute['spare_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }

        return response()->json([
            "message" => "AssetSpare Updated Successfully",
            new AssetSpareResource($asset_spare)
        ]); 
    }

    public function deleteAssetSpare(Request $request)
    {
        $request->validate([
            'asset_spare_id' => 'required|exists:asset_spares,asset_spare_id'
        ]);
        $asset_spare = AssetSpare::withTrashed()->where('asset_spare_id', $request->asset_spare_id)->first();

        if($asset_spare->trashed())
        {
            $asset_spare->restore();
            return response()->json([
                "message" =>"AssetSpare Activated successfully"
            ],200);
        }
        else
        {
            $asset_spare->delete();
            return response()->json([
                "message" =>"AssetSpare Deactivated successfully"
            ], 200); 
        }
    }

    public function forceDeleteAssetSpare(Request $request)
    {
        $request->validate([
            'asset_spare_id' => 'required|exists:asset_spares,asset_spare_id'
        ]);
    
        $asset_spare = AssetSpare::where('asset_spare_id', $request->asset_spare_id)->first();
        $spare = UserSpare::whereHas('userService', function($que) use($asset_spare){
            $que->where('asset_id', $asset_spare->asset_id)->where('asset_zone_id', $asset_spare->asset_zone_id);
        })->where('spare_id', $asset_spare->spare_id)->exists();
        if ($spare) 
        {
            return response()->json([
                "message" => 'Asset Spare cannot be deleted as it is used in other records.'
            ], 400);
        }
        AssetSpareValue::where('asset_spare_id', $request->asset_spare_id)->forceDelete();
        AssetSpare::where('asset_spare_id', $request->asset_spare_id)->forceDelete();

        return response()->json([
            "message" => "AssetSpare deleted successfully"
        ], 200);
    } 

    public function assetSpareAttributeValues(Request $request)
    {
        $request->validate([
            'spare_id' => 'required|exists:spares,spare_id'
        ]);

        $spare_attribute_values = SpareAttributeValue::where('spare_id', $request->spare_id)->get();
        return SpareAttributeValueResource::collection($spare_attribute_values);
    }
}
