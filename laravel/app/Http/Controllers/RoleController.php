<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    public function paginateRoles(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Role::query();

        if(isset($request->role))
        {
            $query->where('role',$request->role);
        }
        
        if($request->search!='')
        {
            $query->where('role', 'like', "%$request->search%")
            ->orWhere('description', 'like', "%$request->search%");
        }
        $role = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return RoleResource::collection($role);
    }

    public function getRoles()
    {
        $role = Role::all();
        return RoleResource::collection($role);
    }
    
    public function addRole(Request $request)
    {
        $data = $request->validate([
            'role' => 'required|string|unique:roles,role',
            'description' => 'nullable'
        ]);

        $role = Role::create($data);
        return response()->json(["message" => "Role Created Successfully"]); 
    }

    public function getRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,role_id'
        ]);

        $role = Role::where('role_id',$request->role_id)->first();
        return new RoleResource($role);
    } 

    public function updateRole(Request $request)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,role_id',
            'role' => 'required|string|unique:roles,role,'.$request->role_id.',role_id',
            'description' => 'nullable'
        ]);

        $role = Role::where('role_id', $request->role_id)->first();
        $role->update($data);
        return response()->json(["message" => "Role Updated Successfully"]);
    }

    public function deleteRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,role_id'
        ]);
        $role = Role::withTrashed()->where('role_id', $request->role_id)->first();
        if($role->trashed()) 
        {
            $role->restore();
            return response()->json([
                "message" =>"Role Activated Successfully"
            ],200);
        } 
        else 
        {
            $role->delete();
            return response()->json([
                "message" =>"Role Deactivated Successfully"
            ], 200); 
        }
    }
}