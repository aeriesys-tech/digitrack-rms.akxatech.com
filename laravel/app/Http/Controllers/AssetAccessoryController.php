<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetAccessoryResource;
use App\Models\AssetAccessory;
use Illuminate\Support\Facades\Auth;

class AssetAccessoryController extends Controller
{
    public function paginateAssetAccessories(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AssetAccessory::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->accessory_id))
        {
            $query->where('accessory_id',$request->accessory_id);
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
            })->orWherehas('Accessory', function($query) use($request){
                $query->where('accessory_name', 'like', "$request->search%");
            });
        }
        $asset_accessory = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return AssetAccessoryResource::collection($asset_accessory);
    }

    public function getAssetAccessories()
    {
        $asset_accessory = AssetAccessory::all();
        return AssetAccessoryResource::collection($asset_accessory);
    }

    public function addAssetAccessory(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $areaId = Auth::User()->Plant->area_id;
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'accessory_type_id' => 'required|accessory_types,accessory_type_id',
            'asset_zone_id' => 'nullable|array', 
            'asset_zone_id.*' => 'nullable|exists:asset_zones,asset_zone_id',
            'accessory_name' => 'required',
            'attachment' => 'nullable'
        ]);

        $data['plant_id'] = $userPlantId;
        $data['area_id'] = $areaId;

        if ($request->hasFile('attachment')) {
            $attachment = time() . '.' . $request->file('attachment')->getClientOriginalExtension();
            $request->file('attachment')->move(public_path('storage/assetAttachments'), $attachment);
            $data['attachment'] = $attachment;
        }

        $createdAccessorys = [];

        if (!empty($data['asset_zone_id'])) 
        {
            foreach ($data['asset_zone_id'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $accessoryData = $data;
                $accessoryData['asset_zone_id'] = $zoneId;

                $assetAccessory = AssetAccessory::create($accessoryData);
                $createdAccessorys[] = new AssetAccessoryResource($assetAccessory);
            }
        } 
        else 
        {
            $accessoryData = $data;
            $accessoryData['asset_zone_id'] = null;

            $assetAccessory = AssetAccessory::create($accessoryData);
            $createdAccessorys[] = new AssetAccessoryResource($assetAccessory);
        }
        return response()->json($createdAccessorys, 201);
    }

    public function getAssetAccessory(Request $request)
    {
        $request->validate([
            'asset_accessory_id' => 'required|exists:asset_accessories,asset_accessory_id'
        ]);

        $asset_accessory = AssetAccessory::where('asset_accessory_id',$request->asset_accessory_id)->first();
        return new AssetAccessoryResource($asset_accessory);
    }

    public function updateAssetAccessory(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $areaId = Auth::User()->Plant->area_id;
        $data = $request->validate([
            'asset_accessory_id' => 'required|exists:asset_accessories,asset_accessory_id',
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zone_id' => 'nullable|asset_zones,asset_zone_id',
            'accessory_type_id' => 'required|accessory_types,accessory_type_id',
            'accessory_name' => 'required',
            'attachment' => 'nullable'
        ]);

        $data['plant_id'] = $userPlantId;
        $data['area_id'] = $areaId;

        $asset_accessory = AssetAccessory::where('asset_accessory_id', $request->asset_accessory_id)->first();
        
        //attachment
        if ($request->hasFile('attachment')) 
        {
            if ($asset_accessory->attachment && file_exists(public_path('storage/assetAttachments/' . $asset_accessory->attachment))) {
                unlink(public_path('storage/assetAttachments/' . $asset_accessory->attachment));
            }

            $attachment = time() . '.' . $request->file('attachment')->getClientOriginalExtension();
            $request->file('attachment')->move(public_path('storage/assetAttachments'), $attachment);
            $data['attachment'] = $attachment;
        }

        $asset_accessory->update($data);
        return new AssetAccessoryResource($asset_accessory); 
    }

    public function deleteAssetAccessory(Request $request)
    {
        $request->validate([
            'asset_accessory_id' => 'required|exists:asset_accessories,asset_accessory_id'
        ]);

        AssetAccessory::where('asset_accessory_id', $request->asset_accessory_id)->delete();

        return response()->json([
            'message' => "AssetAccessory Deleted Successfully"
        ]);
    }
}
