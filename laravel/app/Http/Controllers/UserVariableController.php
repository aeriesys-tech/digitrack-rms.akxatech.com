<?php

namespace App\Http\Controllers;
use App\Models\UserVariable;
use App\Models\AssetZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserVariableResource;
use App\Models\UserAssetVariable;

class UserVariableController extends Controller
{
    public function paginateUserVariables(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $authPlantId = Auth::User()->plant_id;
        $query =  UserVariable::query();

        $query->where('plant_id', $authPlantId);

        if(isset($request->plant_id))
        {
        $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->asset_id))
        {
        $query->where('asset_id',$request->asset_id);
        }
        if(isset($request->user_id))
        {
        $query->where('user_id',$request->user_id);
        }
        
        if($request->search!='')
        {
            $query->where('job_no', 'like', "%$request->search%")
                ->orwhereHas('Asset', function($que) use($request){
                $que->where('asset_code', 'like', "%$request->search%");
            });
        }
        $user_variables = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return UserVariableResource::collection($user_variables);
    }

    // public function addUserVariable(Request $request)
    // {
    //     $request->validate([
    //         'asset_id' => 'required|exists:assets,asset_id',
    //         'job_date' => 'required',
    //         'note' => 'nullable|sometimes',
    //         'asset_variables' => 'required|array',
    //         'asset_variables.*.*.variable_id' => 'required',
    //         'asset_variables.*.*.value' => 'required',
    //     ],[
            
    //         'asset_variables.*.*.value.required' => 'value is required'
    //     ]);
    //     $user_variables = [];
    //     $assetZones = AssetZone::where('asset_id', $request->asset_id)->get();
    //     foreach ($request->asset_variables as $variables) 
    //     {
    //         foreach($variables as $variable)
    //         {
    //             $data = [
    //                 'asset_zone_id' => $variable['asset_zone_id'],
    //                 'plant_id' => Auth::user()->plant_id,
    //                 'user_id' => Auth::user()->user_id,
    //                 'job_no' => $this->generateJobNo(),
    //                 'job_date' => $request->job_date,
    //                 'note' => $request->note,
    //                 'asset_id' => $request->asset_id
    //             ];
    //             $user_variable = UserVariable::create(array_merge($data, [
    //                 'variable_id' => $variable['variable_id'],
    //                 'value' => $variable['value']
    //             ]));
    //             $user_variables[] = $user_variable; 
    //         }
    //     }
    //     return response()->json([
    //         'user_variables' => UserVariableResource::collection($user_variables),
    //         'message' => 'Process Register Created Successfully'
    //     ]);
    // }

    public function addUserVariable(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'job_date' => 'required',
            'note' => 'nullable|sometimes',
        ]);
        $data['job_no'] = $this->generateJobNo();
        $data['plant_id'] = Auth::User()->plant_id;
        $data['user_id'] = Auth::id();

        $user_variable = UserVariable::create($data);

        $assetZones = AssetZone::where('asset_id', $request->asset_id)->get();

        foreach ($request->user_asset_variables as $variables) 
        {
            foreach($variables as $variable)
            {
                UserAssetVariable::create([
                    'user_variable_id' => $user_variable->user_variable_id,
                    'variable_id' =>  $variable['variable_id'],
                    'asset_zone_id' => $variable['asset_zone_id'],
                    'value' => $variable['value'] ?? null
                ]); 
            }
        }

        return response()->json([
            'user_variables' => new UserVariableResource($user_variable),
            'message' => 'Process Register Created Successfully'
        ]);
    }

    public function updateUserVariable(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'job_date' => 'required|date',
            'note' => 'nullable|sometimes'
        ]);
        
        $data['plant_id'] = Auth::User()->plant_id;
        $data['user_id'] = Auth::User()->user_id;

        $user_variable = UserVariable::where('user_variable_id', $request->user_variable_id)->first();
        $user_variable->update($data);
        
        //UserAssetCheck
        foreach($request->user_asset_variables as $asset_variables)
        {
            foreach($asset_variables as $asset_variable)
            {
                $UserAssetVariable = UserAssetVariable::where('user_asset_variable_id', $asset_variable['user_asset_variable_id'])->first();
            
                if ($UserAssetVariable) {
                    $UserAssetVariable->update([
                        'value' => $asset_variable['value']
                    ]);
                }
                else {
                    UserAssetVariable::create([
                        'user_variable_id' => $user_variable->user_variable_id,
                        'variable_id' => $asset_variable['variable_id'],
                        'asset_zone_id' => $asset_variable['asset_zone_id'],
                        'value' => $asset_variable['value']
                    ]);
                }
            }
        }
        return response()->json(["message" => "UserVariable Updated Successfully"]);
    }

    // public function updateUserVariable(Request $request)
    // {
    //     $data = $request->validate([
    //         'user_variable_id' => 'required|exists:user_variables,user_variable_id',
    //         'asset_id' => 'required|exists:assets,asset_id',
    //         'job_date' => 'required',
    //         'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
    //         'note' => 'nullable|sometimes',
    //         'value' => 'required'
    //     ]);

    //     $data['plant_id'] = Auth::User()->plant_id;
    //     $data['user_id'] = Auth::User()->user_id;

    //     $user_variable = UserVariable::where('user_variable_id', $request->user_variable_id)->firstOrFail();
    //     $user_variable->update($data);

    //     return response()->json(["message" => "Process Register Updated Successfully"]);
    // }

    public function getUserVariable(Request $request)
    {
        $request->validate([
            'user_variable_id' => 'required|exists:user_variables,user_variable_id'
        ]);

        $user_variable = UserVariable::where('user_variable_id', $request->user_variable_id)->first();
        return new UserVariableResource($user_variable);
    }

    public function deleteUserVariable(Request $request)
    {
        $request->validate([
            'user_variable_id' => 'required|exists:user_variables,user_variable_id'
        ]);

        UserAssetVariable::where('user_variable_id', $request->user_variable_id)->forceDelete();
        UserVariable::where('user_variable_id', $request->user_variable_id)->forceDelete();

        return response()->json([
            'message' => "UserVariable Deleted Successfully"
        ]);
    }

    public function generateJobNo()
    {
        $reference = UserVariable::latest()->first();
        $nextReferenceNumber = 1; 
        
        if ($reference) {
            $lastReferenceNumber = (int) substr($reference->job_no, 9); 
            $nextReferenceNumber = $lastReferenceNumber + 1;
        }
        
        $formattedNextServiceNumber = str_pad($nextReferenceNumber, 4, '0', STR_PAD_LEFT);
        $job_no = 'Job_' . $formattedNextServiceNumber;
        
        while (UserVariable::where('job_no', $job_no)->exists()) {
            $nextReferenceNumber++;
            $formattedNextServiceNumber = str_pad($nextReferenceNumber, 4, '0', STR_PAD_LEFT);
            $job_no = 'Job_' . $formattedNextServiceNumber;
        }
        return $job_no;
    }
}
