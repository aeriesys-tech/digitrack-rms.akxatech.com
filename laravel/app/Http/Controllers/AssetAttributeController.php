<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetAttribute;
use App\Models\AssetAttributeType;
use App\Http\Resources\AssetAttributeResource;
use Illuminate\Support\Facades\Auth;

class AssetAttributeController extends Controller
{
    public function paginateAssetAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AssetAttribute::query();

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
            ->orwhereHas('AssetAttributeTypes', function($que) use($request){
                $que->whereHas('AssetType', function($qu) use($request){
                    $qu->where('asset_type_name', 'like', "$request->search%");
                });
            });
        }
        $asset_spare = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AssetAttributeResource::collection($asset_spare);
    }

    public function addAssetAttribute(Request $request)
    {
        $data = $request->validate([
        	'field_name' => 'required|string|unique:asset_attributes,field_name,'.$request->asset_attribute_id.',asset_attribute_id',
	        'display_name' => 'required|string|unique:asset_attributes,display_name,'.$request->asset_attribute_id.',asset_attribute_id',
	        'field_type' => 'required', 
	        'field_values' => 'nullable',
	        'field_length' => 'required',
	        'is_required' => 'required|boolean',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);
        $data['user_id'] = Auth::id();
        
        $asset_attribute = AssetAttribute::create($data);

        foreach ($data['asset_types'] as $asset_type_id) {
            AssetAttributeType::create([
                'asset_attribute_id' => $asset_attribute->asset_attribute_id,
                'asset_type_id' => $asset_type_id
            ]);
        }
        return new AssetAttributeResource($asset_attribute);  
    }  

    public function updateAssetAttribute(Request $request)
    {
        $data = $request->validate([
            'asset_attribute_id' => 'required|exists:asset_attributes,asset_attribute_id',
            'field_name' => 'required|string|unique:asset_attributes,field_name,'.$request->asset_attribute_id.',asset_attribute_id',
	        'display_name' => 'required|string|unique:asset_attributes,display_name,'.$request->asset_attribute_id.',asset_attribute_id',
            'field_type' => 'required',
            'field_values' => 'nullable',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'asset_types' => 'required|array',
            'asset_types.*' => 'required|exists:asset_type,asset_type_id'
        ]);

        $data['user_id'] = Auth::id();

        $asset_attribute = AssetAttribute::where('asset_attribute_id', $request->asset_attribute_id)->first();
        $asset_attribute->update($data);

        AssetAttributeType::where('asset_attribute_id', $asset_attribute->asset_attribute_id)->delete();

        foreach ($data['asset_types'] as $asset_type_id) {
            AssetAttributeType::create([
                'asset_attribute_id' => $asset_attribute->asset_attribute_id,
                'asset_type_id' => $asset_type_id
            ]);
        }

        return new AssetAttributeResource($asset_attribute);
    }


    public function getAssetAttribute(Request $request)
    {
        $request->validate([
            'asset_attribute_id' => 'required|exists:asset_attributes,asset_attribute_id'
        ]);

        $asset_attribute = AssetAttribute::where('asset_attribute_id', $request->asset_attribute_id)->first();
        return new AssetAttributeResource($asset_attribute);
    }

    public function getAssetAttributes()
    {
        $asset_attribute = AssetAttribute::all();
        return AssetAttributeResource::collection($asset_attribute);
    }

    public function deleteAssetAttribute(Request $request)
    {
        $request->validate([
            'asset_attribute_id' => 'required|exists:asset_attributes,asset_attribute_id'
        ]);

        $asset_attribute = AssetAttribute::withTrashed()->where('asset_attribute_id', $request->asset_attribute_id)->first();
       
        if($asset_attribute->trashed())
        {
            $asset_attribute->restore();
            return response()->json([
                "message" =>"AssetAttribute Activated successfully"
            ],200);
        }
        else
        {
            $asset_attribute->delete();
            return response()->json([
                "message" =>"AssetAttribute Deactivated successfully"
            ], 200); 
        }
    }
}
