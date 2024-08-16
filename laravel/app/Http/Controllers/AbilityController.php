<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\Module;
use App\Models\Ability;
use App\Models\RoleAbility;
use App\Http\Resources\AbilityResource;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;

class AbilityController extends Controller
{
    public function paginateAbilities(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Ability::query();
        if(isset($request->ability))
        {
            $query->where('ability',$request->ability);
        }
        if(isset($request->module_id))
        {
            $query->where('module_id',$request->module_id);
        }
        if($request->search!='')
        {
            $query->where('ability', 'like', "%$request->search%")
                ->orWhere('module_id', 'like', "%$request->search%");
        }
        $abilities = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return AbilityResource::collection($abilities);
    }

    public function addAbility(Request $request)
    {
        $data = $request->validate([
            'ability' => 'required',
            'description' => 'nullable|sometimes',
            'module_id' => 'required|exists:modules,module_id'
        ]);

        $ability = Ability::create($data);
        return new AbilityResource($ability);
    }

    public function getAbility(Request $request)
    {
        $request->validate([
            'ability_id' => 'required|exists:abilities,ability_id'
        ]);

        $ability = Ability::where('ability_id', $request->ability_id)->first();
        return new AbilityResource($ability);
    }

    public function getAbilities()
    {
        $abilities = Ability::all();
        return AbilityResource::collection($abilities);
    }

    public function updateAbility(Request $request)
    {
        $data = $request->validate([
            'ability' => 'required',
            'description' => 'nullable|sometimes',
            'module_id' => 'required|exists:modules,module_id'
        ]);

        $ability = Ability::where('ability_id', $request->ability_id)->first();
        $ability->update($data);
        return new AbilityResource($ability);
    }

    public function deleteAbility(Request $request)
    {
        $request->validate([
            'ability_id' => 'required|exists:abilities,ability_id'
        ]);

        Ability::where('ability_id', $request->ability_id)->delete();
        return response()->json([
            'Message' => 'Successfully Deleted Ability!'
        ]);
    }

    public function getPermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,role_id'
        ]);
        $permissions = RoleAbility::where('role_id',$request->role_id)->with('Ability')->get();
        return $permissions;
    }

    public function getPermissionStatus(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,role_id'
        ]);
        $abilities = Ability::orderBy('ability','DESC')->get();
        $data=[];
        $permissions = Role::find($request->role_id)->abilities()->orderBy('ability','DESC')->get();
        foreach ($abilities as $value)
        {
            $temp = $value;
            if($permissions->contains($value))
            {
                $temp->setAttribute('status',true);
                array_push($data,$temp);
            }
            else
            {
                $temp->setAttribute('status',false);
                array_push($data,$temp);
            }
        }
        return PermissionResource::collection($data)->collection->groupBy('module_id');
    }

    public function deleteAuthorization(Request $request)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,role_id',
            'ability_id' => 'required|exists:abilities,ability_id',
            'status' => 'required|boolean'
        ]);

        if(count($request->role_abilities))
        {
            foreach ($request->role_abilities as $role_ability) 
            {
                $role_ability_res = '';
                $role_ability_res = RoleAbility::where('role_id',$request->role_id)
                                     ->where('ability_id', $role_ability['ability_id'])
                                     ->first();
                if($role_ability_res){
                    $role_ability_res->delete();
                }else{
                    if($role_ability['status']){
                        RoleAbility::create([
                            'role_id' => $request->role_id,
                            'ability_id' => $role_ability['ability_id'],
                        ]);
                    }
                }
            }
        }
        else
        {
            $role_ability = RoleAbility::where('role_id',$request->role_id)
                                     ->where('ability_id', $request->ability_id)
                                     ->first();
            if($role_ability){
                $role_ability->delete();
            }else{
                RoleAbility::create($data);
            }
        }
        
        return response()->json([
            'message' => 'Authorization updated successfully!'
        ],200);
    }

    public function addPermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,role_id'
        ]);
        $permissions = json_decode(file_get_contents('storage/abilities.json'),true);
        foreach($permissions as $permission)
        {
            $module = Module::firstOrCreate([
                'module_name' => $permission['module']
            ]);
            
            foreach($permission['abilities'] as $ability)
            {
                $ab = Ability::firstOrCreate(
                    [
                        'ability' => $ability['ability']
                    ],
                    [
                        'description' => $ability['description'],
                        'module_id' => $module->module_id
                    ]
                );

                foreach($permission['abilities'] as $ability)
                {
                    $ab = Ability::firstOrCreate(
                        [
                            'ability' => $ability['ability']
                        ],
                        [
                            'description' => $ability['description'],
                            'module_id' => $module->module_id
                        ]
                    );
                    RoleAbility::firstOrCreate([
                        'role_id' => $request->role_id,
                        'ability_id' => $ab->ability_id
                    ]);
                }
            }
        }
    }
}