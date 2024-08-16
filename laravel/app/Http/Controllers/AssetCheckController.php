<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetCheckResource;
use App\Http\Resources\UserAssetCheckResource;
use App\Http\Resources\UserAssetCheckDeviationResource;
use App\Models\AssetCheck;
use App\Models\Check;
use App\Models\UserAssetCheck;
use Illuminate\Support\Facades\Auth;

class AssetCheckController extends Controller
{
    public function paginateAssetChecks(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AssetCheck::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->check_id))
        {
            $query->where('check_id',$request->check_id);
        }
        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orwherehas('Check', function($query) use($request){
                $query->where('check_name', 'like', "$request->search%");
            })->orwherehas('Asset', function($query) use($request){
                $query->where('asset_name', 'like', "$request->search%");
            });
        }
        $asset_check = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AssetCheckResource::collection($asset_check);
    }

    public function addAssetCheck(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'check_id' => [
                'required',
                'exists:checks,check_id',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = AssetCheck::where('check_id', $value)
                        ->where('asset_id', $request->asset_id)
                        ->exists();
                    if ($exists) {
                        $fail('The combination of Check already exists.');
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
        ]);
        $data['plant_id'] = $userPlantId;

        $check = Check::where('check_id', $request->check_id)->first();

        $data['lcl'] = $request->input('lcl', $check->lcl);
        $data['ucl'] = $request->input('ucl', $check->ucl);
        $data['default_value'] = $request->input('default_value', $check->default_value);
        
        $asset_check = AssetCheck::create($data);
        return new AssetCheckResource($asset_check);
    }

    public function getAssetCheck(Request $request)
    {
        $request->validate([
            'asset_check_id' => 'required|exists:asset_checks,asset_check_id'
        ]);

        $asset_check = AssetCheck::where('asset_check_id', $request->asset_check_id)->first();
        return new AssetCheckResource($asset_check);
    }

    public function getAssetChecks(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id'
        ]);
        $asset_check = AssetCheck::where('asset_id', $request->asset_id)->get();
        return AssetCheckResource::collection($asset_check);
    }

    public function updateAssetCheck(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'asset_check_id' => 'required|exists:asset_checks,asset_check_id',
            'lcl' => 'nullable|sometimes',
            'ucl' => 'nullable|sometimes',
            'default_value' =>'nullable|sometimes'
        ]);
        $data['plant_id'] = $userPlantId;

        $asset_check = AssetCheck::where('asset_check_id', $request->asset_check_id)->first();
        $asset_check->update($data);
        return new AssetCheckResource($asset_check);
    }

    public function deleteAssetCheck(Request $request)
    {
        $request->validate([
            'asset_check_id' => 'required|exists:asset_checks,asset_check_id'
        ]);

        $asset_check = AssetCheck::withTrashed()->where('asset_check_id', $request->asset_check_id)->first();

        if($asset_check->trashed())
        {
            $asset_check->restore();
            return response()->json([
                "message" =>"AssetCheck Activated successfully"
            ],200);
        }
        else
        {
            $asset_check->delete();
            return response()->json([
                "message" =>"AssetCheck Deactivated successfully"
            ], 200); 
        }
    }

    public function forceDeleteAssetCheck(Request $request)
    {
        $request->validate([
            'asset_check_id' => 'required|exists:asset_checks,asset_check_id'
        ]);

        try {
            $isAssociated = UserAssetCheck::where('asset_check_id', $request->asset_check_id)->exists();

            if ($isAssociated) {
                return response()->json([
                    "message" => "Cannot delete AssetCheck because it is associated with other records."
                ], 400);
            }

            $asset_check = AssetCheck::where('asset_check_id', $request->asset_check_id)->forceDelete();

            return response()->json([
                "message" => "AssetCheck deleted successfully"
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                "error" => "Cannot delete AssetCheck due to a database error."
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                "error" => "An unexpected error occurred. Please try again."
            ], 500);
        }
    }

    public function deviationAssetChecks(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $authPlantId = Auth::User()->plant_id;
        $query = UserAssetCheck::query();
        $query->whereHas('UserCheck', function($query) use ($authPlantId) {
                $query->where('plant_id', $authPlantId);
            })->whereColumn('default_value', '!=', 'value')->get();

        if (isset($request->department_id)) {
            $query->whereHas('UserCheck', function($quer) use ($request) {
                $quer->whereHas('Asset', function($que) use ($request){
                    $que->where('department_id', $request->department_id);
                });
            });
        }
            
        if($request->search!='')
        {
            $query->where(function($query) use ($request) {
                $query->where('default_value', 'like', "{$request->search}%")
                    ->orWhere('value', 'like', "{$request->search}%")
                    ->orWhereHas('Check', function($que) use ($request) {
                    $que->where('field_name', 'like', "{$request->search}%");
                })->orWhereHas('UserCheck', function($qu) use ($request) {
                    $qu->whereHas('Asset', function($que) use ($request) {
                        $que->where('asset_name', 'like', "{$request->search}%");
                    });
                })->orwhereHas('UserCheck', function($quer) use($request){
                    $quer->whereHas('Asset', function($que) use($request){
                        $que->whereHas('AssetType', function($qu) use($request){
                            $qu->where('asset_type_name', 'like', "{$request->search}%");
                        });
                    });
                })->orwhereHas('UserCheck', function($quer) use($request){
                    $quer->whereHas('Asset', function($que) use($request){
                        $que->whereHas('Department', function($qu) use($request){
                            $qu->where('department_name', 'like', "{$request->search}%");
                        });
                    });
                });
            });
        }
       
    
        // Sort by related table columns
        if ($request->keyword == 'field_name') {
            $query->whereHas('Check', function($q) use ($request) {
                $q->orderBy('field_name', $request->order_by);
            });
        }

        $asset_check = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return UserAssetCheckDeviationResource::collection($asset_check);
    }
}
