<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Check;
use App\Models\CheckAssetType;
use App\Http\Resources\CheckResource;

class CheckController extends Controller
{
    public function paginateChecks(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Check::query();

        if(isset($request->field_name))
        {
            $query->where('field_name',$request->field_name);
        }

        if(isset($request->frequency_id))
        {
            $query->where('frequency_id',$request->frequency_id);
        }
        if(isset($request->field_type))
        {
            $query->where('field_type',$request->field_type);
        }
        if(isset($request->default_value))
        {
            $query->where('default_value',$request->default_value);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "%$request->search%")
                 ->orWhere('field_type', 'like', "$request->search%")
                 ->orWhere('default_value', 'like', "$request->search%");
        }
        $check = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return CheckResource::collection($check);
    }

    public function getChecks()
    {
        $check = Check::all();
        return CheckResource::collection( $check);
    }

    public function addCheck(Request $request)
    {
        $data = $request->validate([
            'field_name' => 'required|unique:checks,field_name',
            'field_type' => 'required',
            'default_value' => 'required',
            'lcl' => 'required_if:field_type,Number',
            'ucl' => 'required_if:field_type,Number',
            'field_values' => 'required_if:field_type,Select',
            'order' => 'required',
            'is_required' => 'required',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'list_parameter_id' => 'required|exists:list_parameters,list_parameter_id'
        ]);
        
        $check = Check::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            CheckAssetType::create([
                'check_id' => $check->check_id,
                'asset_type_id' => $asset_type,
            ]);
        }
        return response()->json(["message" => "Check Created Successfully"]);        
    }  
    
    public function getCheck(Request $request)
    {
        $request->validate([
            'check_id' => 'required|exists:checks,check_id'
        ]);

        $check = Check::where('check_id',$request->check_id)->first();
        return new CheckResource($check);
    }

    public function getAssetTypeChecks(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $checks = Check::whereHas('CheckAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return CheckResource::collection($checks);
    }

    public function updateCheck(Request $request)
    {
        $data = $request->validate([
            'check_id' => 'required|exists:checks,check_id',
            'field_name' => 'required|unique:checks,field_name,'.$request->check_id.',check_id',
            'field_type' => 'required',
            'default_value' => 'required',
            'lcl' => 'required_if:field_type,Number',
            'ucl' => 'required_if:field_type,Number',
            'field_values' => 'required_if:field_type,Select',
            'order' => 'required',
            'is_required' => 'required',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'list_parameter_id' => 'required|exists:list_parameters,list_parameter_id'
        ]);

        $check = Check::where('check_id', $request->check_id)->first();
        $check->update($data);

        CheckAssetType::where('check_id', $check->check_id)->delete();

        foreach ($data['asset_types'] as $asset_type_id) {
            CheckAssetType::create([
                'check_id' => $check->check_id,
                'asset_type_id' => $asset_type_id
            ]);
        }

        return response()->json(["message" => "Check Updated Successfully"]);  
    }
    
    public function deleteCheck(Request $request)
    {
        $request->validate([
            'check_id' => 'required|exists:checks,check_id'
        ]);
        $check = Check::withTrashed()->where('check_id',$request->check_id)->first();

        if($check->trashed())
        {
            $check->restore();
            return response()->json([
                "message" =>"Check Activated successfully"
            ],200);
        }
        else
        {
            $check->delete();
            return response()->json([
                "message" =>"Check Deactivated successfully"
            ], 200);
        }
    }
}
