<?php

namespace App\Http\Controllers;
use App\Models\UserCheck;
use App\Models\UserAssetCheck;
use App\Models\AssetCheck;
use App\Models\UserCheckAttachment;
use App\Models\Check;
use App\Http\Resources\UserCheckResource;
use App\Http\Resources\UserCheckAttachmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use App\Models\AssetZone;
use App\Models\Asset;
use App\Models\Department;
use Carbon\Carbon;
use App\Models\AssetService;

class UserCheckController extends Controller
{
    public function paginateUserChecks(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        // $authPlantId = Auth::User()->plant_id;
        $query =  UserCheck::query();

        // $query->where('plant_id', $authPlantId);

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
            $query->whereBetween('reference_date', [$fromDate, $toDate]);
        }
        
        if($request->search!='')
        {
            $query->where('reference_no', 'like', "%$request->search%")
                ->orwhereHas('Asset', function($que) use($request){
                $que->where('asset_code', 'like', "%$request->search%");
            })->orwhereHas('AssetZone', function($que) use($request){
                $que->where('zone_name', 'like', "%$request->search%");
            });
        }

        if ($request->keyword == 'asset_code') {
            $query->join('assets', 'user_checks.asset_id', '=', 'assets.asset_id')->select('user_checks.*') 
                  ->orderBy('assets.asset_code', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }
        $user_checks = $query->paginate($request->per_page); 
        return UserCheckResource::collection($user_checks);
    }

    public function addUserCheck(Request $request)
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
            'asset_id' => 'required|exists:assets,asset_id',
            'reference_date' => 'required',
            // 'asset_zone_id' => 'required|exists:asset_zones,asset_zone_id',
            'department_id' => 'required|exists:departments,department_id',
            'note' => 'nullable|sometimes',
            'attachments.*' => 'nullable',
            'asset_service_id' => 'required|exists:asset_services,asset_service_id'
        ]);
        $zoneID = AssetService::where('asset_service_id', $request->asset_service_id)->first();
        $data['plant_id'] =  $asset->plant_id;
        $data['user_id'] = Auth::User()->user_id;
        $data['reference_no'] = $this->generateReferanceNo();
        $data['asset_zone_id'] = $zoneID->asset_zone_id;

        $user_check = UserCheck::create($data);
        
        //UserAssetCheck
        foreach($request->asset_checks as $asset_check)
        {
            UserAssetCheck::Create([
                'user_check_id' => $user_check->user_check_id,
                'asset_check_id' => $asset_check['asset_check_id'],
                'check_id' => $asset_check['check_id'],
                'field_name' => $asset_check['field_name'],
                'field_type' => $asset_check['field_type'],
                'default_value' => $asset_check['default_value'],
                'is_required' => $asset_check['is_required'],
                'lcl' => $asset_check['lcl'],
                'ucl' => $asset_check['ucl'],
                'field_values' => $asset_check['field_values'],
                'order' => $asset_check['order'],
                'value' => $asset_check['value'],
            ]);
        }

        if(isset($request->attachments))
        {
            foreach($request->attachments as $attachment)
            {
                $i = 0;
                if(strpos($attachment['attachment'], 'base64') !== false)
                {
                    $image_parts = explode(";base64,", $attachment['attachment']);
                    $image_base64 = base64_decode($image_parts[1]);
                    $image_type_aux = explode($attachment['type']."/", $image_parts[0]);
                    $url = 'asset_'.date('Ymdhis')."_".$i.".".$image_type_aux[1];
                    Storage::disk('s3')->put($url, file_get_contents($attachment['attachment']));

                    $userCheck = UserCheckAttachment::create([
                        'user_check_id' => $user_check->user_check_id,
                        'attachments' => $url,
                    ]);
                }
                $i++;
            }
        }

        return response()->json([
            'user_check' => new UserCheckResource($user_check),
            "message" => "Check Register Created Successfully"
        ]);
    }

    public function updateUserCheck(Request $request)
    {
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'reference_date' => 'required',
            'asset_zone_id' => 'required|exists:asset_zones,asset_zone_id',
            'department_id' => 'required|exists:departments,department_id',
            'note' => 'nullable|sometimes'
        ]);
        
        $data['plant_id'] = $asset->plant_id;
        $data['user_id'] = Auth::User()->user_id;

        $user_check = UserCheck::where('user_check_id', $request->user_check_id)->first();
        $user_check->update($data);
        
        //UserAssetCheck
        foreach($request->asset_checks as $asset_check)
        {
            $UserAssetCheck = UserAssetCheck::where('user_asset_check_id', $asset_check['user_asset_check_id'])->first();

            if($UserAssetCheck)
            {
                $UserAssetCheck->update([
                    'value' => $asset_check['value'],
                ]);
            }
        }
        return response()->json(["message" => "Check Register Updated Successfully"]);
    }

    public function getUserCheck(Request $request)
    {
        $request->validate([
            'user_check_id' => 'required|exists:user_checks,user_check_id'
        ]);

        $user_check = UserCheck::where('user_check_id', $request->user_check_id)->first();
        return new UserCheckResource($user_check);
    }

    public function deleteUserCheck(Request $request)
    {
        $request->validate([
            'user_check_id' => 'required|exists:user_checks,user_check_id'
        ]);

        UserCheckAttachment::where('user_check_id', $request->user_check_id)->delete();
        UserAssetCheck::where('user_check_id', $request->user_check_id)->delete();
        UserCheck::where('user_check_id', $request->user_check_id)->delete();

        return response()->json([
            'message' => "Check Register Deleted Successfully"
        ]);
    }

    public function downloadCheckAttachment(Request $request)
    {
        $file_path = $request->file_name;
        $mime_type = Storage::disk('s3')->mimeType($file_path);
        $headers = [
            'Content-Type' => $mime_type,
            'Content-Disposition' => 'attachment; filename="'. $request->file_name .'"',
        ];
        return \Response::make(Storage::disk('s3')->get($file_path), 200, $headers);
    }

    public function generateReferanceNo()
    {
        $reference = UserCheck::latest()->first();
        $nextReferenceNumber = 1; 
        
        if ($reference) {
            $lastReferenceNumber = (int) substr($reference->reference_no, 9); 
            $nextReferenceNumber = $lastReferenceNumber + 1;
        }
        
        $formattedNextServiceNumber = str_pad($nextReferenceNumber, 4, '0', STR_PAD_LEFT);
        $service_no = 'Reference_' . $formattedNextServiceNumber;
        
        while (UserCheck::where('reference_no', $service_no)->exists()) {
            $nextReferenceNumber++;
            $formattedNextServiceNumber = str_pad($nextReferenceNumber, 4, '0', STR_PAD_LEFT);
            $service_no = 'Reference_' . $formattedNextServiceNumber;
        }
        return $service_no;
    }

    public function getAssetRegisterDepartments(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'department_id' => 'nullable|exists:departments,department_id'
        ]);

        $checkIds = AssetCheck::where('asset_id', $request->asset_id)->pluck('check_id'); 
        $department_ids = Check::whereIn('check_id', $checkIds)->pluck('department_id')->unique()->toArray();

        $asset_departments = Department::whereIn('department_id', $department_ids)->get();
        
        return $asset_departments;
    }

    public function updateDeviation(Request $request)
    {
        $data = $request->validate([
            'user_asset_check_id' => 'required|exists:user_asset_checks,user_asset_check_id',
            'remarks' => 'required'
        ]);

        $data['remark_status'] = true;
        $data['remark_user_id'] = Auth::id();
        $data['remark_date'] = Carbon::now();

        $asset_check = UserAssetCheck::where('user_asset_check_id', $request->user_asset_check_id)->first();
        $asset_check->update($data);

        return response()->json([
            "message" => "Deviation Updated Successfully"
        ]);
    }
}
