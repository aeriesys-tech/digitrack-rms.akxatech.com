<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetSpareResource;
use App\Models\AssetSpare;
use App\Models\Spare;
use Illuminate\Support\Facades\Auth;

class AssetSpareController extends Controller
{
    public function paginateAssetSpares(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
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
        return AssetSpareResource::collection($asset_spare);
    }

    public function addAssetSpare(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'spare_id' => [
                'required',
                'exists:spares,spare_id',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = AssetSpare::where('spare_id', $value)
                        ->where('asset_id', $request->asset_id)
                        ->exists();
                    if ($exists) {
                        $fail('The combination of Spare already exists.');
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'area_id' => 'required|exists:areas,area_id',
            'area_zone_id' => 'required|area_zones,area_zone_id'
        ]);

        $data['plant_id'] = $userPlantId;

        $asset_spare = AssetSpare::create($data);
        return new AssetSpareResource($asset_spare);
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
            'asset_id' => 'required|exists:assets,asset_id'
        ]);
        $spare_ids = AssetSpare::where('asset_id', $request->asset_id)->get('spare_id')->toArray();
        $asset_spare = Spare::whereIn('spare_id', $spare_ids)->get();
        return $asset_spare;
    }

    public function updateAssetSpare(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'asset_spare_id' => 'required|exists:asset_spares,asset_spare_id',
            'spare_id' => 'required|exists:spares,spare_id',
            'asset_id' => 'required|exists:assets,asset_id',
            'area_id' => 'required|exists:areas,area_id',
            'area_zone_id' => 'required|area_zones,area_zone_id'
        ]);

        $data['plant_id'] = $userPlantId;

        $asset_spare = AssetSpare::where('asset_spare_id', $request->asset_spare_id)->first();
        $asset_spare->update($data);
        return new AssetSpareResource($asset_spare); 
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
    
        $asset_spare = AssetSpare::where('asset_spare_id', $request->asset_spare_id)->forceDelete();

        return response()->json([
            "message" => "AssetSpare deleted successfully"
        ], 200);
    }    
}
