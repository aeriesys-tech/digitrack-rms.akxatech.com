<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakDownList;
use App\Models\BreakDownListAssetType;
use App\Http\Resources\BreakDownListResource;

class BreakDownListController extends Controller
{
    public function paginateBreakDownLists(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = BreakDownList::query();

        if(isset($request->break_down_list_code))
        {
            $query->where('break_down_list_code',$request->break_down_list_code);
        }
        if(isset($request->break_down_list_name))
        {
            $query->where('break_down_list_name',$request->break_down_list_name);
        }
              
        if($request->search!='')
        {
            $query->where('break_down_list_code', 'like', "%$request->search%")
                ->orWhere('break_down_list_name', 'like', "$request->search%");
        }
        $break_down_list = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return BreakDownListResource::collection($break_down_list);
    }

    public function getBreakDownLists()
    {
        $break_down_list = BreakDownList::all();
        return BreakDownListResource::collection($break_down_list);
    }

    public function addBreakDownList(Request $request)
    {
        $data = $request->validate([
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id',
            'break_down_list_code' => 'required|string|unique:break_down_lists,break_down_list_code',
            'break_down_list_name' => 'required|string|unique:break_down_lists,break_down_list_name',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);
        
        $break_down_list = BreakDownList::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            BreakDownListAssetType::create([
                'break_down_list_id' => $break_down_list->break_down_list_id,
                'asset_type_id' => $asset_type,
            ]);
        }
        return response()->json(["message" => "BreakDownList Created Successfully"]);  
    } 
    
    public function getBreakDownList(Request $request)
    {
        $request->validate([
            'break_down_list_id' => 'required|exists:break_down_lists,break_down_list_id'
        ]);

        $break_down_list = BreakDownList::where('break_down_list_id',$request->break_down_list_id)->first();
        return new BreakDownListResource($break_down_list);
    }

    public function getAssetTypeBreakDownLists(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $break_down_lists = BreakDownList::whereHas('BreakDownListAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return BreakDownListResource::collection($break_down_lists);
    }

    public function updateBreakDownList(Request $request)
    {
        $data = $request->validate([
            'break_down_list_id' => 'required|exists:break_down_lists,break_down_list_id',
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id',
            'break_down_list_code' => 'required|string|unique:break_down_lists,break_down_list_code,'.$request->break_down_list_id.',break_down_list_id',
            'break_down_list_name' => 'required|string|unique:break_down_lists,break_down_list_name,'.$request->break_down_list_id.',break_down_list_id',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);

        $break_down_list = BreakDownList::where('break_down_list_id', $request->break_down_list_id)->first();
        $break_down_list->update($data);

        BreakDownListAssetType::where('break_down_list_id', $break_down_list->break_down_list_id)->delete();

        foreach ($data['asset_types'] as $asset_type_id) {
            BreakDownListAssetType::create([
                'break_down_list_id' => $break_down_list->break_down_list_id,
                'asset_type_id' => $asset_type_id
            ]);
        }

        return response()->json(["message" => "BreakDownList Updated Successfully"]);
    }

    public function deleteBreakDownList(Request $request)
    {
        $request->validate([
            'break_down_list_id' => 'required|exists:break_down_lists,break_down_list_id'
        ]);
        $break_down_list = BreakDownList::withTrashed()->where('break_down_list_id', $request->break_down_list_id)->first();

        if($break_down_list->trashed())
        {
            $break_down_list->restore();
            return response()->json([
                "message" => "BreakDownList Activated successfully"
            ],200);
        }
        else
        {
            $break_down_list->delete();
            return response()->json([
                "message" => "BreakDownList Deactivated successfully"
            ], 200);
        }
    }
}
