<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserService;
use App\Models\UserSpare;
use App\Http\Resources\UserServiceResource;
use App\Http\Resources\UserServicePendingResource;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AssetZone;
use App\Models\AssetSpare;

class UserServiceController extends Controller
{
    public function paginateUserServices(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);
        $authPlantId = Auth::User()->plant_id;
        $query = UserService::query();

        $query->where('plant_id', $authPlantId);

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->user_id))
        {
            $query->where('user_id',$request->user_id);
        }
        if(isset($request->service_id))
        {
            $query->where('service_id',$request->service_id);
        }
        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }
              
        if($request->search!='')
        {
            $query->where('service_no', 'like', "%$request->search%")
                ->orWhere('service_cost', 'like', "%$request->search%")
                ->orWhereHas('Service', function($quer) use($request){
                    $quer->where('service_name', 'like', "%$request->search%");
                })->orWhereHas('Asset', function($que) use($request){
                    $que->where('asset_code', 'like', "%$request->search%");
                });
        }
        $user_service = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return UserServiceResource::collection($user_service);
    }

    public function addUserService(Request $request)
    {
        // $assetZone = AssetZone::where('asset_id', $request->asset_id)->first();
        // if ($assetZone) {
        //     $request->validate([
        //         'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
        //     ]);
        // } 
        // else {
        //     $data['asset_zone_id'] = $request->input('asset_zone_id', null);
        // }

        $data = $request->validate([
            // 'service_id' => 'required|exists:services,service_id',
            // 'service_cost' => 'nullable|sometimes',
            'asset_id' => 'required|exists:assets,asset_id',
            // 'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
            'service_date' => 'required',
            'next_service_date' => 'nullable|sometimes',
            'note' => 'nullable|sometimes',
            'is_latest' => 'nullable|sometimes|boolean'
        ]);

        $data['service_no'] = $this->generateServiceNo();
        $data['plant_id'] = Auth::User()->plant_id;
        $data['user_id'] = Auth::User()->user_id;

        // //Previus False

        // UserService::where('asset_id', $data['asset_id'])
        //     ->where('service_id', $data['service_id'])
        //     ->update(['is_latest' => false]);

        $userServiceIds = UserSpare::whereHas('userService', function ($query) use ($request) {
            $query->where('asset_id', $request->asset_id);
        })->where('service_id', $request->service_id)->pluck('user_service_id');
    
        // Update is_latest to false for these user_service_id
        UserService::whereIn('user_service_id', $userServiceIds)->update(['is_latest' => false]);

        //New Value
        $data['is_latest'] = true;

        $service = UserService::create($data);

        if ($request->has('user_spares')) 
        {
            foreach($request->user_spares as $spare) 
            {
                //quantity from the AssetSpare
                $assetSpare = AssetSpare::where('asset_id', $request->asset_id)->where('spare_id', $spare['spare_id'])->first();

                    if (!$assetSpare) {
                        return response()->json([
                        'errors' => "No AssetSpare found for asset ID {$request->asset_id} and spare ID {$spare['spare_id']}."
                        ], 404);
                    }

                    // Check if the user-entered quantity exceeds the available quantity
                    if ($spare['quantity'] > $assetSpare->quantity) {
                        return response()->json([
                        'errors' => "The quantity for spare ID {$spare['spare_id']} exceeds the available stock of {$assetSpare->quantity}."
                        ], 422);
                    }

                UserSpare::create([
                    'user_service_id' => $service->user_service_id,
                    'service_id' => $spare['service_id'],
                    'service_cost' => $spare['service_cost'],
                    'asset_zone_id' => $spare['asset_zone_id'],
                    'spare_id' => $spare['spare_id'],
                    'spare_cost' => $spare['spare_cost'],
                    'quantity' => $spare['quantity']
                ]);
            }
        }

        return response()->json(['message' => 'Service Register created successfully'], 201);
    }

    public function getUserService(Request $request)
    {
        $request->validate([
            'user_service_id' => 'required|exists:user_services,user_service_id'
        ]);

        $service = UserService::where('user_service_id', $request->user_service_id)->first();
        return new UserServiceResource($service);
    }

    public function getUserServices()
    {
        $services = UserService::all();
        return UserServiceResource::collection($services);
    }

    public function updateUserService(Request $request)
    {
        // $assetZone = AssetZone::where('asset_id', $request->asset_id)->first();
        // if ($assetZone) {
        //     $request->validate([
        //         'asset_zone_id' => 'required|exists:asset_zones,asset_zone_id',
        //     ]);
        // } 
        // else {
        //     $data['asset_zone_id'] = $request->input('asset_zone_id', null);
        // }
        
        $data = $request->validate([
            'user_service_id' => 'required|exists:user_services,user_service_id',
            // 'service_id' => 'required|exists:services,service_id',
            // 'service_cost' => 'nullable|sometimes',
            'asset_id' => 'required|exists:assets,asset_id',
            // 'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
            'service_date' => 'required',
            'next_service_date' => 'nullable|sometimes',
            'note' => 'nullable|sometimes',
            'deleted_user_spares' => 'nullable|array'
        ]);

        $data['plant_id'] = Auth::User()->plant_id;
        $data['user_id'] = Auth::User()->user_id;

        $service = UserService::where('user_service_id', $request->user_service_id)->first();
        $service->update($data);

        foreach ($request->user_spares as $spare) 
        {
            // Fetch available quantity from AssetSpares for the given asset_id and spare_id
            $assetSpare = AssetSpare::where('asset_id', $request->asset_id)->where('spare_id', $spare['spare_id'])->first();

            if (!$assetSpare) {
                return response()->json([
                'error' => "No AssetSpare found for asset ID {$request->asset_id} and spare ID {$spare['spare_id']}."
                ], 404);
            }

            // Check if the user-entered quantity exceeds the available quantity
            if ($spare['quantity'] > $assetSpare->quantity) {
                return response()->json([
                'error' => "The quantity for spare ID {$spare['spare_id']} exceeds the available stock of {$assetSpare->quantity}."
                ], 422);
            }

            // Update or Create
            $userSpare = UserSpare::where('user_spare_id', $spare['user_spare_id'])->first();
            if ($userSpare) {
                $userSpare->update([
                    'user_service_id' => $service->user_service_id,
                    'spare_id' => $spare['spare_id'],
                    'spare_cost' => $spare['spare_cost'],
                    'service_id' => $spare['service_id'],
                    'service_cost' => $spare['service_cost'],
                    'asset_zone_id' => $spare['asset_zone_id'],
                    'quantity' => $spare['quantity'],
                ]);
            }
            else {
                UserSpare::create([
                    'user_service_id' => $service->user_service_id,
                    'spare_id' => $spare['spare_id'],
                    'spare_cost' => $spare['spare_cost'],
                    'service_id' => $spare['service_id'],
                    'service_cost' => $spare['service_cost'],
                    'asset_zone_id' => $spare['asset_zone_id'],
                    'quantity' => $spare['quantity'],
                ]);
            }
        }

        UserSpare::whereIn('user_spare_id',$request->deleted_user_spares)->forceDelete();
        return response()->json(['message' => 'Service Register updated successfully'], 201);
    }

    public function deleteUserService(Request $request)
    {
        $request->validate([
            'user_service_id' => 'required|exists:user_services,user_service_id'
        ]);

        UserSpare::where('user_service_id', $request->user_service_id)->delete();
        UserService::where('user_service_id', $request->user_service_id)->delete();

        return response()->json([
            "message" => "UserService Deleted Successfully"
        ]);
    }

    public function deleteUserSpare(Request $request)
    {
        $request->validate([
            'user_spare_id' => 'required|exists:user_spares,user_spare_id'
        ]);

        UserSpare::where('user_spare_id', $request->user_spare_id)->delete();
        return response()->json([
            "message" => "UserSpare Deleted Successfully"
        ]);
    }

    public function generateServiceNo()
    {
        $service = UserService::latest()->first();
        $nextServiceNumber = 1; 
        
        if ($service) {
            $lastServiceNumber = (int) substr($service->service_no, 9); 
            $nextServiceNumber = $lastServiceNumber + 1;
        }
        
        $formattedNextServiceNumber = str_pad($nextServiceNumber, 4, '0', STR_PAD_LEFT);
        $service_no = 'Service_' . $formattedNextServiceNumber;
        
        while (UserService::where('service_no', $service_no)->exists()) {
            $nextServiceNumber++;
            $formattedNextServiceNumber = str_pad($nextServiceNumber, 4, '0', STR_PAD_LEFT);
            $service_no = 'Service_' . $formattedNextServiceNumber;
        }
        return $service_no;
    }

    public function getPendingServices(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);
    
        $authPlantId = Auth::User()->plant_id;

        $query = UserService::query();
        $query->where('plant_id', $authPlantId)
        ->where('next_service_date', '<=', Carbon::now())->where('is_latest', true)->get();

        if (isset($request->department_id)) {
            $query->whereHas('Asset', function($quer) use ($request) {
                $quer->where('department_id', $request->department_id);
            });
        }

        if($request->search!='')
        {
            $query->where(function($query) use ($request) {
                $query->where('service_date', 'like', "{$request->search}%")
                    ->orWhere('service_no', 'like', "{$request->search}%")->orWhere('service_cost', 'like', "{$request->search}%")  
                    ->orWhere('next_service_date', 'like', "{$request->search}%") 
                    ->orwhereHas('Asset', function($que) use ($request) {
                        $que->where('asset_code', 'like', "{$request->search}%");
                    })->orwhereHas('Asset', function($que) use($request){
                        $que->whereHas('AssetType', function($qu) use($request){
                            $qu->where('asset_type_name', 'like', "{$request->search}%");
                    });
                })->orwhereHas('Service', function($que) use($request){
                    $que->where('service_name', 'like', "{$request->search}%");
                })->orwhereHas('Asset', function($quer) use($request){
                    $quer->whereHas('Department', function($que) use($request){
                        $que->where('department_name', 'like', "{$request->search}%");
                    });
                });
            });
        }

        if ($request->keyword == 'service_id') {
            $query->whereHas('Service', function($q) use ($request) {
                $q->orderBy('service_id', $request->order_by);
            });
        }elseif ($request->keyword == 'asset_id') {
            $query->whereHas('Asset', function($q) use ($request) {
                $q->orderBy('asset_id', $request->order_by);
            });
        }

        $user_service = $query->orderBy($request->keyword, $request->order_by)->paginate($request->per_page);
        return UserServicePendingResource::collection($user_service);
    }

    public function getUpcomingServices(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);
    
        $authPlantId = Auth::User()->plant_id;
        
        $query = UserService::query();
        $query->where('plant_id', $authPlantId)
          ->where('next_service_date', '>=', Carbon::now())->where('is_latest', true)->get();

        if (isset($request->department_id)) {
            $query->whereHas('Asset', function($quer) use ($request) {
                $quer->where('department_id', $request->department_id);
            });
        }

        if($request->search!='')
        {
            $query->where(function($query) use ($request) {
                $query->where('service_date', 'like', "{$request->search}%")
                    ->orWhere('service_no', 'like', "{$request->search}%")->orWhere('service_cost', 'like', "{$request->search}%")  
                    ->orWhere('next_service_date', 'like', "{$request->search}%") 
                    ->orwhereHas('Asset', function($que) use ($request) {
                        $que->where('asset_code', 'like', "{$request->search}%");
                    })->orwhereHas('Asset', function($que) use($request){
                        $que->whereHas('AssetType', function($qu) use($request){
                            $qu->where('asset_type_name', 'like', "{$request->search}%");
                    });
                })->orwhereHas('Service', function($que) use($request){
                    $que->where('service_name', 'like', "{$request->search}%");
                })->orwhereHas('Asset', function($quer) use($request){
                    $quer->whereHas('Department', function($que) use($request){
                        $que->where('department_name', 'like', "{$request->search}%");
                    });
                });
            });
        }

        if ($request->keyword == 'service_id') {
            $query->whereHas('Service', function($q) use ($request) {
                $q->orderBy('service_id', $request->order_by);
            });
        }elseif ($request->keyword == 'asset_id') {
            $query->whereHas('Asset', function($q) use ($request) {
                $q->orderBy('asset_id', $request->order_by);
            });
        }

        $user_service = $query->orderBy($request->keyword, $request->order_by)->paginate($request->per_page);
        return UserServicePendingResource::collection($user_service);
    }
}
