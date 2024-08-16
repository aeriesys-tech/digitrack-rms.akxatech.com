<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetType;
use App\Http\Resources\AssetTypeResource;

class AssetTypeController extends Controller
{
    public function paginateAssetTypes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AssetType::query();

        if(isset($request->asset_type_code))
        {
            $query->where('asset_type_code',$request->asset_type_code);
        }
        if(isset($request->asset_type_name))
        {
            $query->where('asset_type_name',$request->asset_type_name);
        }
        
        if($request->search!='')
        {
            $query->where('asset_type_code', 'like', "%$request->search%")
                ->orWhere('asset_type_name', 'like', "$request->search%");
        }
        $asset_type = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AssetTypeResource::collection($asset_type);
    }

    public function getAssetTypes()
    {
        $asset_type = AssetType::all();
        return AssetTypeResource::collection($asset_type);
    }

    public function addAssetType(Request $request)
    {
        $data = $request->validate([
            'asset_type_code' => 'required|string|unique:asset_type,asset_type_code',
            'asset_type_name' => 'required|string|unique:asset_type,asset_type_name'
        ]);
        
        $asset_type = AssetType::create($data);
        return response()->json(["message" => "AssetType Created Successfully"]);  
    }  

    public function getAssetType(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $asset_type = AssetType::where('asset_type_id',$request->asset_type_id)->first();
        return new AssetTypeResource($asset_type);
    }

    public function updateAssetType(Request $request)
    {
        $data = $request->validate([ 
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'asset_type_code' => 'required|string|unique:asset_type,asset_type_code,'.$request->asset_type_id.',asset_type_id',
            'asset_type_name' => 'required|string|unique:asset_type,asset_type_name,'.$request->asset_type_id.',asset_type_id'
        ]);

        $asset_type = AssetType::where('asset_type_id', $request->asset_type_id)->first();
        $asset_type->update($data);
        return response()->json(["message" => "AssetType Updated Successfully"]); 
    }

    public function deleteAssetType(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);
        $asset_type = AssetType::withTrashed()->where('asset_type_id', $request->asset_type_id)->first();

        if($asset_type->trashed())
        {
            $asset_type->restore();
            return response()->json([
                "message" =>"AssetType Activated successfully"
            ],200);
        }
        else
        {
            $asset_type->delete();
            return response()->json([
                "message" =>"AssetType Deactivated successfully"
            ], 200); 
        }
    }
}
