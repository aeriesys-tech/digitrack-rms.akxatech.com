<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetParameter;
use App\Models\AssetParameterType;
use App\Http\Resources\AssetParameterResource;
use Illuminate\Support\Facades\Auth;

class AssetParameterController extends Controller
{
    public function paginateAssetParameters(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AssetParameter::query();

        if(isset($request->field_name))
        {
            $query->where('field_name',$request->field_name);
        }
        if(isset($request->display_name))
        {
            $query->where('display_name',$request->display_name);
        }
        if(isset($request->field_values))
        {
            $query->where('field_values',$request->field_values);
        }

        if(isset($request->asset_type_id))
        {
            $query->where('asset_type_id',$request->asset_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "$request->search%")
            ->orwhere('display_name', 'like', "$request->search%")->orwhere('field_values', 'like', "$request->search%")
            ->orwhere('field_type', 'like', "$request->search%")->orwhere('field_length', 'like', "$request->search%")
            ->orwhereHas('AssetType', function($que) use($request){
                $que->where('asset_type_name', 'like', "$request->search%");
            });
        }
        $asset_spare = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AssetParameterResource::collection($asset_spare);
    }

    public function addAssetParameter(Request $request)
    {
        $data = $request->validate([
        	'field_name' => 'required',
	        'display_name' => 'required',
	        'field_type' => 'required', 
	        'field_values' => 'nullable',
	        'field_length' => 'required',
	        'is_required' => 'required|boolean',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);
        $data['user_id'] = Auth::id();
        
        $asset_parameter = AssetParameter::create($data);

        foreach ($data['asset_types'] as $asset_tpe_id) {
            AssetParameterType::create([
                'asset_parameter_id' => $asset_parameter->asset_parameter_id,
                'asset_type_id' => $asset_tpe_id
            ]);
        }
        return new AssetParameterResource($asset_parameter);  
    }  

    // public function updateAssetParameter(Request $request)
    // {
    //     $data = $request->validate([
	// 	'asset_parameter_id' => 'required|exists:asset_parameters,asset_parameter_id',
	// 	'field_name' => 'required',
	// 	'display_name' => 'required',
	// 	'field_type' => 'required', 
	// 	'field_values' => 'required',
	// 	'field_length' => 'required',
	// 	'is_required' => 'required|boolean',
	// 	'asset_type_id' => 'required|exists:asset_type,asset_type_id'
    //     ]);

    //     $data['user_id'] = Auth::id();
        
    //     $asset_parameter = AssetParameter::where('asset_parameter_id', $request->asset_parameter_id)->first();
	//     $asset_parameter->update($data);
	//     return new AssetParameterResource($asset_parameter);  
    // }  

    public function updateAssetParameter(Request $request)
    {
        $data = $request->validate([
            'asset_parameter_id' => 'required|exists:asset_parameters,asset_parameter_id',
            'field_name' => 'required',
            'display_name' => 'required',
            'field_type' => 'required',
            'field_values' => 'nullable',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'asset_types' => 'required|array',
            'asset_types.*' => 'required|exists:asset_type,asset_type_id'
        ]);

        $data['user_id'] = Auth::id();

        $asset_parameter = AssetParameter::where('asset_parameter_id', $request->asset_parameter_id)->first();
        $asset_parameter->update($data);

        AssetParameterType::where('asset_parameter_id', $asset_parameter->asset_parameter_id)->delete();

        foreach ($data['asset_types'] as $asset_type_id) {
            AssetParameterType::create([
                'asset_parameter_id' => $asset_parameter->asset_parameter_id,
                'asset_type_id' => $asset_type_id
            ]);
        }

        return new AssetParameterResource($asset_parameter);
    }


    public function getAssetParameter(Request $request)
    {
        $request->validate([
            'asset_parameter_id' => 'required|exists:asset_parameters,asset_parameter_id'
        ]);

        $asset_parameter = AssetParameter::where('asset_parameter_id', $request->asset_parameter_id)->first();
        return new AssetParameterResource($asset_parameter);
    }

    public function getAssetParameters()
    {
        $asset_parameter = AssetParameter::all();
        return AssetParameterResource::collection($asset_parameter);
    }

    public function deleteAssetParameter(Request $request)
    {
        $request->validate([
            'asset_parameter_id' => 'required|exists:asset_parameters,asset_parameter_id'
        ]);

        $asset_parameter = AssetParameter::withTrashed()->where('asset_parameter_id', $request->asset_parameter_id)->first();
       
        if($asset_parameter->trashed())
        {
            $asset_parameter->restore();
            return response()->json([
                "message" =>"AssetParameter Activated successfully"
            ],200);
        }
        else
        {
            $asset_parameter->delete();
            return response()->json([
                "message" =>"AssetParameter Deactivated successfully"
            ], 200); 
        }
    }
}
