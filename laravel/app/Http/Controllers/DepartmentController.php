<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    public function paginateDepartments(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Department::query();

        if(isset($request->department_code))
        {
            $query->where('department_code',$request->department_code);
        }
        if(isset($request->department_name))
        {
            $query->where('department_name',$request->department_name);
        }
        
        if($request->search!='')
        {
            $query->where('department_code', 'like', "%$request->search%")
                 ->orWhere('department_name', 'like', "$request->search%");
        }
        $frequency = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return DepartmentResource::collection($frequency);
    }

    public function addDepartment(Request $request)
    {
        $data = $request->validate([
            'department_code' => 'required|unique:departments,department_code',
            'department_name' => 'required|unique:departments,department_name'
        ]);

        $department = Department::create($data);
        return response()->json([
            "message" => "Department Created Successfully"
        ]); 
    }

    public function getDepartments()
    {
        $departments = Department::all();
        return DepartmentResource::collection($departments);
    }

    public function getDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id'
        ]);

        $department = Department::where('department_id', $request->department_id)->first();
        return new DepartmentResource($department);
    }

    public function updateDepartment(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'department_code' => 'required|unique:departments,department_code,'.$request->department_id.',department_id',
            'department_name' => 'required|unique:departments,department_name,'.$request->department_id.',department_id'
        ]);

        $department = Department::where('department_id', $request->department_id)->first();
        $department->update($data);
        return response()->json([
            "message" => "Department Updated Successfully"
        ]);
    }

    public function deleteDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
        ]);
        $department = Department::withTrashed()->where('department_id', $request->department_id)->first();

        if($department->trashed())
        {
            $department->restore();
            return response()->json([
                "message" => "Department Activated successfully"
            ],200);
        }
        else
        {
            $department->delete();
            return response()->json([
                "message" => "Department Deactivated successfully"
            ], 200);
        }
    }
}
