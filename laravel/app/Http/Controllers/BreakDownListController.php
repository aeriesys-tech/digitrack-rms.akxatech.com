<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakDownList;
use App\Models\BreakDownListAssetType;
use App\Http\Resources\BreakDownListResource;
use App\Models\BreakDownAttribute;
use App\Http\Resources\BreakDownAttributeResource;
use App\Models\BreakDownAttributeValue;

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
            $query->where('job_no', 'like', "%$request->search%")
            ->orwhereHas('BreakDownType', function($que) use($request){
                $que->where('break_down_type_name', 'like', "%$request->search%");
            })->orwhereHas('Asset', function($que) use($request){
                $que->where('asset_name', 'like', "%$request->search%");
            });
        }

        if ($request->keyword == 'asset_name') {
            $query->join('assets', 'break_down_lists.asset_id', '=', 'assets.asset_id')->select('break_down_lists.*') 
                  ->orderBy('assets.asset_name', $request->order_by);
        }
        elseif ($request->keyword == 'break_down_type_name') {
            $query->join('break_down_types', 'break_down_lists.break_down_type_id', '=', 'break_down_types.break_down_type_id')->select('break_down_lists.*') 
                  ->orderBy('break_down_types.break_down_type_name', $request->order_by);
        }  
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }

        $break_down_list = $query->withTrashed()->paginate($request->per_page); 
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
            'break_down_attributes' => 'nullable|array',
            'break_down_attributes.*.break_down_attribute_id' => 'nullable|exists:variable_attributes,variable_attribute_id',
            'break_down_attributes.*.break_down_attribute_value.field_value' => 'nullable',
            'job_date' => 'required',
            'note' => 'nullable',
            'asset_id' => 'required|exists:assets,asset_id'
        ]);

        $data['job_no'] = $this->generateBreakDownNo();
        $break_down_list = BreakDownList::create($data);

        foreach ($request->break_down_attributes as $attribute) 
        {
            BreakDownAttributeValue::create([
                'break_down_list_id' => $break_down_list->break_down_list_id,
                'break_down_attribute_id' => $attribute['break_down_attribute_id'],
                'field_value' => $attribute['break_down_attribute_value']['field_value'] ?? '',
            ]);
        }            
        return response()->json(["message" => "BreakDown Register Created Successfully"]);  
    } 
    
    public function getBreakDownList(Request $request)
    {
        $request->validate([
            'break_down_list_id' => 'required|exists:break_down_lists,break_down_list_id'
        ]);

        $break_down_list = BreakDownList::where('break_down_list_id',$request->break_down_list_id)->first();
        return new BreakDownListResource($break_down_list);
    }

    public function getBreakDownData(Request $request)
    {
        $request->validate([
            'break_down_list_id' => 'required|exists:break_down_lists,break_down_list_id'
        ]);

        $break_down = BreakDownList::where('break_down_list_id',$request->break_down_list_id)->first();
        return new BreakDownListResource($break_down);
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
            'break_down_attributes' => 'nullable|array',
            'break_down_attributes.*.break_down_attribute_id' => 'nullable|exists:variable_attributes,variable_attribute_id',
            'break_down_attributes.*.break_down_attribute_value.field_value' => 'nullable',
            'job_date' => 'required',
            'note' => 'nullable',
            'asset_id' => 'required|exists:assets,asset_id',
            'deleted_break_down_attribute_values' => 'nullable|sometimes'
        ]);

        $break_down_list = BreakDownList::where('break_down_list_id', $request->break_down_list_id)->first();
        $break_down_list->update($data);

        if($request->deleted_break_down_attribute_values > 0)
        {
            BreakDownAttributeValue::whereIn('break_down_attribute_value_id', $request->deleted_break_down_attribute_values)->forceDelete();
        }

        foreach ($request->break_down_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? $attribute['break_down_attribute_value']['field_value'] ?? null;
    
            if ($fieldValue !== null) {
                BreakDownAttributeValue::updateOrCreate(
                    [
                        'break_down_list_id' => $break_down_list->break_down_list_id,
                        'break_down_attribute_id' => $attribute['break_down_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
        return response()->json(["message" => "BreakDown Register Updated Successfully"]);
    }

    public function deleteBreakDownList(Request $request)
    {
        $request->validate([
            'break_down_list_id' => 'required|exists:break_down_lists,break_down_list_id'
        ]);

        BreakDownAttributeValue::where('break_down_list_id', $request->break_down_list_id)->forceDelete();
        BreakDownList::where('break_down_list_id', $request->break_down_list_id)->forceDelete();

        return response()->json([
            "message" => "BreakDownRegister Deleted successfully"
        ], 200);
    }

    public function getBreakDownsDropdown(Request $request)
    {
        $request->validate([
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id'
        ]);

        $break_down_type = BreakDownAttribute::whereHas('BreakDownAttributeTypes', function($que) use($request){
            $que->where('break_down_type_id', $request->break_down_type_id);
        })->get();

        return BreakDownAttributeResource::collection($break_down_type);
    }

    public function generateBreakDownNo()
    {
        $break_down = BreakDownList::latest()->first();
        $nextServiceNumber = 1; 
        
        if ($break_down) {
            $lastServiceNumber = (int) substr($break_down->service_no, 9); 
            $nextServiceNumber = $lastServiceNumber + 1;
        }
        
        $formattedNextServiceNumber = str_pad($nextServiceNumber, 4, '0', STR_PAD_LEFT);
        $job_no = 'BreakDown_' . $formattedNextServiceNumber;
        
        while (BreakDownList::where('job_no', $job_no)->exists()) {
            $nextServiceNumber++;
            $formattedNextServiceNumber = str_pad($nextServiceNumber, 4, '0', STR_PAD_LEFT);
            $job_no = 'BreakDown_' . $formattedNextServiceNumber;
        }
        return $job_no;
    }
}
