<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function paginateUsers(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);
        
        // $authPlantId = auth()->User()->plant_id;

        $query = User::query();
        // $query->where('plant_id', $authPlantId);

        if(isset($request->name))
        {
            $query->where('name',$request->name);
        }
        if(isset($request->email))
        {
            $query->where('email',$request->email);
        }
        if(isset($request->mobile_no))
        {
            $query->where('mobile_no',$request->mobile_no);
        }
        
        if($request->search!='')
        {
            $query->where('name', 'like', "%$request->search%")
                 ->orWhere('email', 'like', "$request->search%")
                 ->orWhere('mobile_no', 'like', "%$request->search%")
                 ->orwhereHas('Role', function($que) use($request){
                    $que->where('role', 'like', "%$request->search%");
                 });
        }

        if ($request->keyword == 'role') {
            $query->join('roles', 'users.role_id', '=', 'roles.role_id')->select('users.*') 
                  ->orderBy('roles.role', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }

        $user = $query->withTrashed()->paginate($request->per_page); 
        return UserResource::collection($user);
    }

    public function getUsers()
    {
        $user = User::all();
        return UserResource::collection($user);
    }
    
    public function addUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|max:100|regex:/^[a-z0-9]+([._][a-z0-9]+)*@[a-z0-9]+([.-][a-z0-9]+)*\.[a-z]{2,}$/|unique:users,email',
            'password' => 'required|string',
            'mobile_no' => 'required|regex:/^\+?[6-9]\d{9}$/',
            'role_id' => 'required|exists:roles,role_id',
            'address' => 'nullable',
            'department_id' => 'required|exists:departments,department_id'
        ]);

        $data['password'] = Hash::make($request->password);
        $data['plant_id'] = Auth::User()->plant_id;
        $user = User::create($data);
        return response()->json(["message" => "User Created Successfully"]);   
    }

    public function getUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id'
        ]);

        $user = User::where('user_id',$request->user_id)->first();
        return new UserResource($user);
    } 

    public function updateUser(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'name' => 'required|string',
            'email' => 'required|email|max:100|regex:/^[a-z0-9]+([._][a-z0-9]+)*@[a-z0-9]+([.-][a-z0-9]+)*\.[a-z]{2,}$/|unique:users,email,'.$request->user_id.',user_id',
            'mobile_no' => 'required|regex:/^\+?[6-9]\d{9}$/',
            'role_id' => 'required|exists:roles,role_id',
            'address' => 'nullable',
            'department_id' => 'required|exists:departments,department_id'
        ]);

        $data['plant_id'] = Auth::User()->plant_id;

        $user = User::where('user_id', $request->user_id)->first();
        $user->update($data);
        return response()->json(["message" => "User Updated Successfully"]);   
    }

    public function deleteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id'
        ]);
        $user = User::withTrashed()->where('user_id', $request->user_id)->first();
        if($user->trashed()) 
        {
            $user->restore();
            return response()->json([
                "message" =>"User Activated Successfully"
            ],200);
        } 
        else 
        {
            $user->delete();
            return response()->json([
                "message" =>"User Deactivated Successfully"
            ], 200); 
        }
    }
}

