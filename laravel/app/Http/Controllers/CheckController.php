<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Check;
use App\Models\CheckAssetType;
use App\Http\Resources\CheckResource;
use App\Models\Asset;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CheckExport;
use App\Exports\CheckHeadingsExport;
use App\Imports\CheckImport;

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

        // if(isset($request->department_id))
        // {
        //     $query->whereHas('Department',function($que) {
        // });
        // }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "%$request->search%")
                ->orWhere('field_type', 'like', "$request->search%")
                ->orWhere('default_value', 'like', "$request->search%")
                ->orwhereHas('Department', function($que) use($request){
                    $que->where('department_name', 'like', "$request->search%");
                })->orwhereHas('CheckAssetTypes', function($que) use($request){
                    $que->whereHas('AssetType', function($qu) use($request){
                        $qu->where('asset_type_name', 'like', "$request->search%");
                    });
                });
        }

        if ($request->keyword == 'department_name') {
            $query->join('departments', 'checks.department_id', '=', 'departments.department_id')->select('checks.*') 
                  ->orderBy('departments.department_name', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }

        $check = $query->withTrashed()->paginate($request->per_page); 
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
            'default_value' => [
                'nullable',
                'required_if:field_type,Select',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->field_type === 'Select') {
                        $fieldValuesArray = explode(',', $request->field_values);
                        if (!in_array($value, $fieldValuesArray)) {
                            $fail("The default value must be one of the options in field values: " . implode(', ', $fieldValuesArray));
                        }
                    }
                }
            ],
            'lcl' => 'required_if:field_type,Number|numeric',
            'ucl' => 'required_if:field_type,Number|numeric',
            'field_values' => 'nullable|required_if:field_type,Select',
            'order' => 'required',
            'is_required' => 'required',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'department_id' => 'required|exists:departments,department_id'
        ],[
            'field_values.required_if' => 'The field values field is required when the field type is Dropdown.',
            'default_value.required_if' => 'The default value field is required when field type is Dropdown.',
        ]);
        
        $check = Check::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            CheckAssetType::firstOrCreate([
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
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'department_id' => 'nullable|exists:departments,department_id'
        ]);

        if(isset($request->department_id))
        {
            $asset = Asset::where('department_id', $request->department_id)->where('asset_type_id', $request->asset_type_id)->first();
            if ($asset) 
            {
                $checks = Check::whereHas('CheckAssetTypes', function($que) use ($request) {
                    $que->where('asset_type_id', $request->asset_type_id);
                })->where('department_id', $request->department_id)->get();
            } 
        }
        else {
            $checks = Check::whereHas('CheckAssetTypes', function($que) use ($request) {
                $que->where('asset_type_id', $request->asset_type_id);
            })->get();
        }
        return CheckResource::collection($checks);
    }


    public function updateCheck(Request $request)
    {
        $data = $request->validate([
            'check_id' => 'required|exists:checks,check_id',
            'field_name' => 'required|unique:checks,field_name,'.$request->check_id.',check_id',
            'field_type' => 'required',
            'default_value' => [
                'nullable',
                'required_if:field_type,Select',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->field_type === 'Select') {
                        $fieldValuesArray = explode(',', $request->field_values);
                        if (!in_array($value, $fieldValuesArray)) {
                            $fail("The default value must be one of the options in field values: " . implode(', ', $fieldValuesArray));
                        }
                    }
                }
            ],
            'lcl' => 'required_if:field_type,Number|numeric',
            'ucl' => 'required_if:field_type,Number|numeric',
            'field_values' => 'required_if:field_type,Select',
            'order' => 'required',
            'is_required' => 'required',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'department_id' => 'required|exists:departments,department_id'
        ]);

        $check = Check::where('check_id', $request->check_id)->first();
        $check->update($data);

        if(isset($request->deleted_check_asset_types) > 0)
        {
            CheckAssetType::whereIn('check_asset_type_id', $request->deleted_check_asset_types)->forceDelete();
        }

        foreach ($data['asset_types'] as $asset_type_id) 
        {
            $checkType = CheckAssetType::where('check_id', $check->check_id)->where('asset_type_id', $asset_type_id)->first();
            if($checkType)
            {
                $checkType->update([
                    'asset_type_id' => $asset_type_id
                ]);
            }
            else {
                CheckAssetType::create([
                    'check_id' => $check->check_id,
                    'asset_type_id' => $asset_type_id
                ]);
            }
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
                "message" =>"Check Activated Successfully"
            ],200);
        }
        else
        {
            $check->delete();
            return response()->json([
                "message" =>"Check Deactivated Successfully"
            ], 200);
        }
    }

    public function downloadChecks(Request $request)
    {
        $filename = "Check.xlsx";

        $excel = new CheckExport();

        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function downloadCheckHeadings()
    {
        $filename = "Check Headings.xlsx";
        $excel = new CheckHeadingsExport();
        
        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importCheck(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CheckImport, $request->file('file'));

        return response()->json(['success' => 'Data imported successfully!']);
    }

    public function deleteHardcheck(Request $request)
    {
        $request->validate([
            'check_id' => 'required|exists:checks,check_id'
        ]);
       
        CheckAssetType::whereIn('check_id', $request->check_id)->forceDelete();
        Check::whereIn('check_id', $request->check_id)->forceDelete();

        return response()->json([
            "message" =>"Check Deleted Successfully"
        ],200);
    }
}