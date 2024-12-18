<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\UserService;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ExportPendingJobs;
use App\Jobs\ExportDeviations;
use App\Jobs\ExportRegistersJob;
use App\Jobs\ExportActivityRegistersJob;
use App\Jobs\ExportServiceRegistersJob;
use App\Jobs\ExportCheckRegistersJob;
use App\Jobs\ExportProcessRegistersJob;
use App\Jobs\ExportBreakDownRegistersJob;
use App\Http\Resources\UserServicePendingResource;
use App\Models\UserAssetCheck;
use App\Http\Resources\UserAssetCheckDeviationResource;
use App\Models\DownloadedReport;
use App\Models\UserCheck;
use App\Http\Resources\DownloadedReportResource;
use App\Http\Resources\UserActivityResource;
use App\Http\Resources\UserServiceResource;
use App\Http\Resources\UserCheckResource;

class ReportController extends Controller
{
    public function getAssetTypeAssets(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $assets = Asset::where('asset_type_id', $request->asset_type_id)->get();
        return $assets;
    }
    
    public function paginatePendingJobs(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $query = UserService::query();
        $query->where('next_service_date', '<', Carbon::now())->where('is_latest', true)->get();

        if (isset($request->asset_id)) {
            $query->where('asset_id', $request->asset_id);
        }

        if (isset($request->department_id)) {
            $query->whereHas('Asset', function($quer) use ($request) {
                $quer->where('department_id', $request->department_id);
            });
        }
     
        if (!empty($request->from_date) && !empty($request->to_date)) 
        {
            $fromDate = $request->from_date;
            $toDate = $request->to_date . ' 23:59:59';
            $query->whereBetween('service_date', [$fromDate, $toDate]);
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

    public function downloadPendingJobs(Request $request)
    {
        $request->validate([
            'asset_id' => 'nullable|sometimes',
            'department_id' => 'nullable|sometimes',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date'
        ]);

        $query = UserService::query();
        $query->where('next_service_date', '<', Carbon::now())->where('is_latest', true);

        if (isset($request->asset_id)) {
            $query->where('asset_id', $request->asset_id);
        }

        if (isset($request->department_id)) {
            $query->whereHas('Asset', function ($quer) use ($request) {
                $quer->where('department_id', $request->department_id);
            });
        }

        if (!empty($request->from_date) && !empty($request->to_date)) 
        {
            $fromDate = $request->from_date;
            $toDate = $request->to_date . ' 23:59:59';
            $query->whereBetween('service_date', [$fromDate, $toDate]);
        }

        $data = $query->get();
        ExportPendingJobs::dispatch($data, Auth::id());

        return response()->json([
            'message' => 'Pending Job Report Download Started! Please wait for a while and then check in the <b>Downloaded Reports</b>.',
        ]);
    }

    public function paginateDeviations(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date'
        ]);
    
        $query = UserAssetCheck::query();
    
        $query->where(function ($query) 
        {
            $query->where(function ($q) {
                $q->where('field_type', 'Number')
                  ->where(function ($subQuery) {
                    $subQuery->whereColumn('value', '<', 'lcl')->orWhereColumn('value', '>', 'ucl');
                  });
            })->orWhere(function ($q) {
                $q->where('field_type', 'Select')
                  ->whereColumn('default_value', '!=', 'value');
            });
        });
    
        if (!empty($request->department_id)) {
            $query->whereHas('UserCheck.Asset', function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            });
        }

        if ($request->remark_status !== null) {
            if ($request->remark_status === 'All') {
            } 
            elseif($request->remark_status === 'Active') {
                $query->where('remark_status', false);
            }
            elseif($request->remark_status === 'Closed') {
                $query->where('remark_status', true);
            }
        }
    
        if (!empty($request->asset_id)) {
            $query->whereHas('UserCheck', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
    
        if (!empty($request->from_date) && !empty($request->to_date)) 
        {
            $fromDate = $request->from_date;
            $toDate = $request->to_date . ' 23:59:59';
        
            $query->whereHas('UserCheck', function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('reference_date', [$fromDate, $toDate]);
            });
        }
        
        $asset_check = $query->orderBy($request->keyword, $request->order_by)->paginate($request->per_page);
        return UserAssetCheckDeviationResource::collection($asset_check);
    }
    
    public function downloadDeviations(Request $request)
    {
        $request->validate([
            'asset_id' => 'nullable|sometimes',
            'department_id' => 'nullable|sometimes',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date'
        ]);
    
        $query = UserAssetCheck::query();

        $query->where(function ($query) {
            $query->where(function ($q) {
                $q->where('field_type', 'Number')
                ->where(function ($subQuery) {
                    $subQuery->whereColumn('value', '<', 'lcl')->orWhereColumn('value', '>', 'ucl');
                });
            })->orWhere(function ($q) {
                $q->where('field_type', 'Select')
                ->whereColumn('default_value', '!=', 'value');
            });
        });
    
        if (!empty($request->department_id)) {
            $query->whereHas('UserCheck.Asset', function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            });
        }
    
        if ($request->remark_status !== null) 
        {
            if ($request->remark_status === 'All') {
            } 
            elseif($request->remark_status === 'Active') {
                $query->where('remark_status', false);
            }
            elseif($request->remark_status === 'Closed') {
                $query->where('remark_status', true);
            }
        }

        if (!empty($request->asset_id)) {
            $query->whereHas('UserCheck', function ($query) use ($request) {
                $query->where('asset_id', $request->asset_id);
            });
        }
    
        if (!empty($request->from_date) && !empty($request->to_date)) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date . ' 23:59:59';
    
            $query->whereHas('UserCheck', function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('reference_date', [$fromDate, $toDate]);
            });
        }
        $data = $query->with(['UserCheck.Asset.Department', 'UserCheck.Asset.AssetType', 'Check'])->get();

        ExportDeviations::dispatch($data, Auth::id());
    
        return response()->json([
            'message' => 'Deviation Report Download Started! Please wait for a while and then check in the <b>Downloaded Reports</b>.',
        ]);
    }    

    public function paginateDownloadReports(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

 
        $query = DownloadedReport::query();
        // $query->whereHas('User', function($que) use($authPlantId){
        //     $que->where('plant_id',  $authPlantId);
        // });

        if($request->search!='')
        {
            $query->where('date_time', 'like', "%$request->search%");
        }
        $download = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return DownloadedReportResource::collection($download);
    }

    public function deleteDownloadReport(Request $request)
    {
        $request->validate([
            'download_report_id' => 'required|exists:downloaded_reports,download_report_id'
        ]);

        $download = DownloadedReport::where('download_report_id', $request->download_report_id)->first();
        if ($download && $download->file_name) {
            $filePath = public_path('storage/reports/' . $download->file_name);
            if (file_exists($filePath)) {
                unlink($filePath); 
            }

            $download->delete();
        }
        return response()->json([
            "message" => "Report Deleted Successfully"
        ]);
    }

    public function downloadAllRegisters(Request $request)
    {
        $request->validate([
            'asset_id' => 'nullable|sometimes',
            'department_id' => 'nullable|sometimes',
            'register' => 'nullable|sometimes',
            'from_date' => 'nullable|sometimes',
            'to_date' => 'nullable|sometimes',
        ]);

        if($request->register == "all_registers")
        {
            $user = Auth::user();
            ExportRegistersJob::dispatch($request->all(), Auth::id());

            return response()->json([
                'message' => 'Registers Report Download Started! Please wait for a while and then check in the <b>Downloaded Reports</b>.',
            ]);
        }

        if($request->register == "activity_register")
        {
            $user = Auth::user();
            ExportActivityRegistersJob::dispatch($request->all(), Auth::id());

            return response()->json([
                'message' => 'Activity Registers Report Download Started! Please wait for a while and then check in the <b>Downloaded Reports</b>.',
            ]);
        }

        if($request->register == "service_register")
        {
            $user = Auth::user();
            ExportServiceRegistersJob::dispatch($request->all(), Auth::id());

            return response()->json([
                'message' => 'Service Register Report Download Started! Please wait for a while and then check in the <b>Downloaded Reports</b>.',
            ]);
        }

        if($request->register == "check_register")
        {
            $user = Auth::user();
            ExportCheckRegistersJob::dispatch($request->all(), Auth::id());

            return response()->json([
                'message' => 'Check Register Report Download Started! Please wait for a while and then check in the <b>Downloaded Reports</b>.',
            ]);
        }

        if($request->register == "process_register")
        {
            $user = Auth::user();
            ExportProcessRegistersJob::dispatch($request->all(), Auth::id());

            return response()->json([
                'message' => 'Process Register Report Download Started! Please wait for a while and then check in the <b>Downloaded Reports</b>.',
            ]);
        }
    }   

    public function downloadBreakDownRegister(Request $request)
    {
        $request->validate([
            'asset_id' => 'nullable|sometimes',
            'department_id' => 'nullable|sometimes',
            'register' => 'nullable|sometimes',
            'from_date' => 'nullable|sometimes',
            'to_date' => 'nullable|sometimes',
        ]);

        $user = Auth::user();
        ExportBreakDownRegistersJob::dispatch($request->all(), Auth::id());

        return response()->json([
            'message' => 'BreakDown Report Download Started! Please wait for a while and then check in the <b>Downloaded Reports</b>.',
        ]);
    }
}