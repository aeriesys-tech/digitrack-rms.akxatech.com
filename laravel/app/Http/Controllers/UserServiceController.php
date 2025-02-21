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
use App\Models\Asset;
use App\Models\AssetSpare;
use App\Models\User;
use App\Mail\ServiceReminderMarkdownMail;
use Illuminate\Support\Facades\Mail;

class UserServiceController extends Controller
{
    public function paginateUserServices(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);
        // $authPlantId = Auth::User()->plant_id;
        $query = UserService::query();

        // $query->where('plant_id', $authPlantId);

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

        if (isset($request->department_id)) {
            $query->whereHas('Asset', function($quer) use ($request) {
                $quer->whereHas('AssetDepartment', function($que) use($request){
                    $que->where('department_id', $request->department_id);
                });
            });
        }
        if (!empty($request->from_date) && !empty($request->to_date)) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date . ' 23:59:59';
            $query->whereBetween('service_date', [$fromDate, $toDate]);
        }
              
        if($request->search!='')
        {
            $query->where('service_no', 'like', "%$request->search%")
                ->orWhereHas('UserSpare', function($quer) use($request){
                    $quer->whereHas('Service', function($que) use($request){
                        $que->where('service_name','like', "%$request->search%");
                    });
                })->orwhereHas('Asset', function($quer) use($request){
                    $quer->where('asset_code', 'like', "%$request->search%");
                })->orwhere('service_date', 'like', "%$request->search%")->orwhere('next_service_date', 'like', "%$request->search%");
        }

        if ($request->keyword == 'asset_code') {
            $query->join('assets', 'user_services.asset_id', '=', 'assets.asset_id')->select('user_services.*') 
                  ->orderBy('assets.asset_code', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }
        
        $user_service = $query->paginate($request->per_page); 
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
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $data = $request->validate([
            // 'service_id' => 'required|exists:services,service_id',
            // 'service_cost' => 'nullable|sometimes',
            'asset_id' => 'required|exists:assets,asset_id',
            // 'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
            'service_date' => 'required',
            'next_service_date' => 'nullable|sometimes|after:service_date',
            'note' => 'nullable|sometimes',
            'is_latest' => 'nullable|sometimes|boolean'
        ]);

        $data['service_no'] = $this->generateServiceNo();
        $data['plant_id'] = $asset->plant_id;
        $data['user_id'] = Auth::User()->user_id;

        // //Previus False

        // UserService::where('asset_id', $data['asset_id'])
        //     ->where('service_id', $data['service_id'])
        //     ->update(['is_latest' => false]);

        // $asset_zone = AssetZone::where('asset_id', $request->asset_id)->first();

        // $userServiceIds = UserSpare::whereHas('userService', function ($query) use ($request) {
        //     $query->where('asset_id', $request->asset_id);
        // })->where('service_id', $request->service_id)->where('asset_zone_id', $asset_zone->asset_zone_id)->pluck('user_service_id');
    
        // // Update is_latest to false for these user_service_id
        // UserService::whereIn('user_service_id', $userServiceIds)->update(['is_latest' => false]);

        foreach ($request->user_spares as $spare) 
        {
            $service_id = $spare['service_id'];
            $asset_zone_id = $spare['asset_zone_id'];
    
            $existingUserServiceIds = UserSpare::whereHas('userService', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            })
            ->where('service_id', $service_id)
            ->where('asset_zone_id', $asset_zone_id)
            ->pluck('user_service_id');
    
            UserService::whereIn('user_service_id', $existingUserServiceIds)->update(['is_latest' => false]);
        }

        //New Value
        $data['is_latest'] = true;

        $service = UserService::create($data);

        if ($request->has('user_spares')) 
        {
            foreach($request->user_spares as $spare) 
            {
                UserSpare::create([
                    'user_service_id' => $service->user_service_id,
                    'service_id' => $spare['service_id'],
                    'service_cost' => $spare['service_cost'],
                    'asset_zone_id' => $spare['asset_zone_id'],
                    'spare_id' => $spare['spare_id'],
                    'spare_cost' => $spare['spare_cost'],
                    'quantity' => $spare['quantity'] ?? null
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
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $data = $request->validate([
            'user_service_id' => 'required|exists:user_services,user_service_id',
            // 'service_id' => 'required|exists:services,service_id',
            // 'service_cost' => 'nullable|sometimes',
            'asset_id' => 'required|exists:assets,asset_id',
            // 'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
            'service_date' => 'required',
            'next_service_date' => 'nullable|sometimes|after:service_date',
            'note' => 'nullable|sometimes',
            'deleted_user_spares' => 'nullable|array'
        ]);

        $data['plant_id'] = $asset->plant_id;
        $data['user_id'] = Auth::User()->user_id;

        $service = UserService::where('user_service_id', $request->user_service_id)->first();
        $service->update($data);

        foreach ($request->user_spares as $spare) 
        {
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
            "message" => "Service Register Deleted Successfully"
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
    
        // $authPlantId = Auth::User()->plant_id;
        $query = UserService::query();

        // $query->where('plant_id', $authPlantId)
        $query->where('next_service_date', '<', Carbon::today())->where('is_latest', true)->get();

        if (isset($request->department_id)) 
        {
            $query->whereHas('Asset', function($assetQuery) use ($request) {
                $assetQuery->whereHas('AssetDepartment', function($deptQuery) use($request){
                    $deptQuery->where('department_id', $request->department_id);
                });
            });
        }

        if (isset($request->asset_id)) 
        {
            $query->where('asset_id', $request->asset_id);
        }
    
        if($request->search!='')
        {
            $query->where(function($query) use ($request) {
                $query->where('service_date', 'like', "%{$request->search}%")
                    ->orWhere('service_no', 'like', "%{$request->search}%")  
                    ->orWhere('next_service_date', 'like', "%{$request->search}%") 
                    ->orwhereHas('Asset', function($que) use ($request) {
                        $que->where('asset_code', 'like', "%{$request->search}%");
                    })->orwhereHas('Asset', function($que) use($request){
                        $que->whereHas('AssetType', function($qu) use($request){
                            $qu->where('asset_type_name', 'like', "%{$request->search}%");
                    });
                })->orwhereHas('UserSpare', function($que) use($request){
                    $que->whereHas('Service',function($qu) use($request){
                        $qu->where('service_name', 'like', "%{$request->search}%");
                    });
                })->orwhereHas('Asset.AssetDepartment.Department', function ($qu) use ($request) {
                    $qu->where('department_name', 'like', "%{$request->search}%");
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
    
        // $authPlantId = Auth::User()->plant_id;
        
        $query = UserService::query();
        // $query->where('plant_id', $authPlantId)
          $query->whereBetween('next_service_date', [Carbon::today(), Carbon::today()->addDays(6)])->where('is_latest', true)->get();

        if (isset($request->department_id)) 
        {
            $query->whereHas('Asset', function($assetQuery) use ($request) {
                $assetQuery->whereHas('AssetDepartment', function($deptQuery) use($request){
                    $deptQuery->where('department_id', $request->department_id);
                });
            });
        }

        if (isset($request->asset_id)) 
        {
            $query->where('asset_id', $request->asset_id);
        }

        if($request->search!='')
        {
            $query->where(function($query) use ($request) {
                $query->where('service_date', 'like', "%{$request->search}%")
                    ->orWhere('service_no', 'like', "%{$request->search}%")  
                    ->orWhere('next_service_date', 'like', "%{$request->search}%") 
                    ->orwhereHas('Asset', function($que) use ($request) {
                        $que->where('asset_code', 'like', "%{$request->search}%");
                    })->orwhereHas('Asset', function($que) use($request){
                        $que->whereHas('AssetType', function($qu) use($request){
                            $qu->where('asset_type_name', 'like', "%{$request->search}%");
                    });
                })->orwhereHas('UserSpare', function($que) use($request){
                    $que->whereHas('Service',function($qu) use($request){
                        $qu->where('service_name', 'like', "%{$request->search}%");
                    });
                })->orwhereHas('Asset.AssetDepartment.Department', function ($qu) use ($request) {
                    $qu->where('department_name', 'like', "%{$request->search}%");
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

    public function sendUpcomingServiceMails(Request $request)
    {
        $userServices = UserService::whereDate('next_service_date', Carbon::now()->toDateString())
            ->where('is_latest', true)->whereHas('Asset.Department', function ($query) {
                $query->whereNotNull('asset_departments.department_id');
            })->with('Asset', 'Asset.Department', 'User', 'User.Plant')->get();

        foreach ($userServices as $service) 
        {
            $departments = $service->Asset->Department;
            foreach ($departments as $department) 
            {
                $departmentUsers = User::where('department_id', $department->department_id)->get();
                $serviceDetails = [
                    'asset_code' => $service->Asset->asset_code ?? '',
                    'service_date' => $service->service_date,
                    'next_service_date' => $service->next_service_date,
                    'service_name' => $service->UserSpare->map(function ($userSpare) {
                        return $userSpare->Service->service_name ?? '';
                    })->filter()->implode(', ')
                ];

                foreach ($departmentUsers as $user) 
                {
                    if ($user) {
                        Mail::to($user->email)->send(new ServiceReminderMarkdownMail($user->name, [$serviceDetails]));
                    }
                    else{
                        return response()->json(["message" => "User Not Found"]);
                    }
                }
            }
        }

        return response()->json(['message' => 'Upcoming Jobs mail sent successfully']);
    }
}
