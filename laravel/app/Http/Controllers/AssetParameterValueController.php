<?php

namespace App\Http\Controllers;
use App\Models\AssetParameterValue;
use Illuminate\Http\Request;
use App\Http\Resources\AssetParameterValueResource;

class AssetParameterValueController extends Controller
{
    public function paginateAssetParameterValues(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AssetParameterValue::query();

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
                ->orWhere('asset_name', 'like', "$request->search%");
        }
        $asset = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AssetParameterValueResource::collection($asset);
    }

    public function addAssetParameterValue(Request $request)
    {
        $data = $request->validate([
            'asset_code' => 'required',
            'asset_name' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_parameter_id' => 'required|exists:asset_parameters,asset_parameter_id',
            'field_value' => 'required'
        ]);

        $asset = AssetParameterValue::create($data);
        return new AssetParameterValueResource($asset);
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

        $asset = Asset::where('asset_id',$request->asset_id)->first();
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
            'asset_code' => 'required|string|unique:assets,asset_code,'.$request->asset_id.',asset_id',
            'asset_name' => 'required|string|unique:assets,asset_name,'.$request->asset_id.',asset_id',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'voltage_id' => 'required|exists:voltages,voltage_id',
            'watt_rating_id' => 'required|exists:watt_ratings,watt_rating_id',
            'frame_id' => 'required|exists:frames,frame_id',
            'mounting_id' => 'required|exists:mountings,mounting_id',
            'section_id' => 'required|exists:sections,section_id',
            'make_id' => 'required|exists:makes,make_id',
            'speed_id' => 'required|exists:speeds,speed_id',
            'serial_no' => 'nullable|sometimes'
        ]);
        $data['plant_id'] = $userPlantId;

        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $asset->update($data);
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

        $asset_type = AssetParameter::whereHas('AssetParameterTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();

        return AssetParameterResource::collection($asset_type);
    }
}
