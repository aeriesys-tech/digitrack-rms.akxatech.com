<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ActivityAttributeResource;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityAttributeType;
use App\Models\ActivityAttribute;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivityAttributeExport;
use App\Exports\ActivityAttributeHeadingsExport;
use App\Imports\ActivityAttributesImport;

class ActivityAttributeController extends Controller
{
    public function paginateActivityAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = ActivityAttribute::query();

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

        if(isset($request->reason_id))
        {
            $query->where('reason_id',$request->reason_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "%$request->search%")
            ->orwhere('display_name', 'like', "%$request->search%")->orwhere('field_values', 'like', "%$request->search%")
            ->orwhere('field_type', 'like', "%$request->search%")->orwhere('field_length', 'like', "%$request->search%")
            ->orwhereHas('ActivityAttributeTypes', function($que) use($request){
                $que->whereHas('Reason', function($qu) use($request){
                    $qu->where('reason_name', 'like', "%$request->search%" );
                });
            });    
        }
        $activity = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return ActivityAttributeResource::collection($activity);
    }

    public function addActivityAttribute(Request $request)
    {
        $data = $request->validate([
            'field_name' => 'required|string|unique:variable_attributes,field_name',
            'display_name' => 'required|string|unique:variable_attributes,display_name',
            'field_type' => 'required', 
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'activity_types' => 'required|array', 
            'activity_types.*' => 'required|exists:reasons,reason_id' 
        ]);

        $data['user_id'] = Auth::id();

        $activity = ActivityAttribute::create($data);

        foreach ($data['activity_types'] as $reason) 
        {
            ActivityAttributeType::create([
                'activity_attribute_id' => $activity->activity_attribute_id,
                'reason_id' => $reason
            ]);
        }
        return new ActivityAttributeResource($activity);
    }

    public function getActivityAttributes()
    {
        $activity = ActivityAttribute::all();
        return ActivityAttributeResource::collection($activity);
    }

    public function getActivityAttribute(Request $request)
    {
        $request->validate([
            'activity_attribute_id' => 'required|exists:activity_attributes,activity_attribute_id'
        ]);

        $activity = ActivityAttribute::where('activity_attribute_id', $request->activity_attribute_id)->first();
        return new ActivityAttributeResource($activity);
    }

    public function updateActivityAttribute(Request $request)
    {
        $data = $request->validate([
            'activity_attribute_id' => 'required|exists:activity_attributes,activity_attribute_id',
            'field_name' => 'required|unique:data_source_attributes,field_name,'.$request->data_source_attribute_id .',data_source_attribute_id',
	        'display_name' => 'required|unique:data_source_attributes,display_name,'.$request->data_source_attribute_id .',data_source_attribute_id',
            'field_type' => 'required',
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'activity_types' => 'required|array',
            'activity_types.*' => 'required|exists:reasons,reason_id'
        ]);

        $data['user_id'] = Auth::id();

        $activity = ActivityAttribute::where('activity_attribute_id', $request->activity_attribute_id)->first();
        $activity->update($data);

        if(isset($request->deleted_activity_types) > 0)
        {
            ActivityAttributeType::whereIn('activity_attribute_type_id', $request->deleted_activity_types)->forceDelete();
        }

        foreach ($data['activity_types'] as $reason_id) 
        {
            $activityType = ActivityAttributeType::where('activity_attribute_id', $activity->activity_attribute_id)
                ->where('reason_id', $reason_id)->first();

            if ($activityType) 
            {
                $activityType->update([
                    'reason_id' => $reason_id
                ]);
            } 
            else 
            {
                ActivityAttributeType::create([
                    'activity_attribute_id' => $activity->activity_attribute_id,
                    'reason_id' => $reason_id
                ]);
            }
        }
        return new ActivityAttributeResource($activity);
    }

    public function deleteActivityAttribute(Request $request)
    {
        $request->validate([
            'activity_attribute_id' => 'required|exists:activity_attributes,activity_attribute_id'
        ]);

        $activity = ActivityAttribute::withTrashed()->where('activity_attribute_id', $request->activity_attribute_id)->first();
       
        if($activity->trashed())
        {
            $activity->restore();
            return response()->json([
                "message" => "Activity Attribute Activated Successfully"
            ],200);
        }
        else
        {
            $activity->delete();
            return response()->json([
                "message" => "Activity Attribute Deactivated Successfully"
            ], 200); 
        }
    }

    public function downloadActivityAttributes(Request $request)
    {
        $filename = "Activity Attributes.xlsx";

        $excel = new ActivityAttributeExport();

        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function downloadActivityAttributeHeadings()
    {
        $filename = "Activity Attribute Headings.xlsx";
        $excel = new ActivityAttributeHeadingsExport();
        
        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importActivityAttribute(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ActivityAttributesImport, $request->file('file'));

        return response()->json(['success' => 'Data imported successfully!']);
    }
}
