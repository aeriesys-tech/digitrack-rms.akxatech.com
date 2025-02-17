<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;  
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RefractoryExport;
use App\Models\UserSpare;
use App\Models\DownloadedReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ExportTotalQuantitySpareJob implements ShouldQueue
{
    use  Dispatchable, InteractsWithQueue, Queueable;

    protected $request;
    protected $userId;

    public function __construct($request, $userId)
    {
        $this->request = $request;
        $this->userId = $userId;
    }

    public function handle()
    {
        $activities = $this->fetchRefractories();

        $filename = 'refractory_consumption_report_' . now()->format('Ymd_His') . '.xlsx';
        $directoryPath = public_path('storage/reports');

        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $path = $directoryPath . '/' . $filename;
        $excel = Excel::raw(new RefractoryExport($activities), \Maatwebsite\Excel\Excel::XLSX);
        file_put_contents($path, $excel);

        DownloadedReport::create([
            'user_id' => $this->userId,
            'date_time' => Carbon::now(),
            'file_name' => $filename,
            'report_name' => 'Refractory_Consumption',
        ]);
    }

    private function fetchRefractories()
    {
        $query = UserSpare::query();
    
        $query->selectRaw('
            user_spares.spare_id, 
            user_services.asset_id,
            SUM(user_spares.quantity) as quantity, 
            MAX(user_spares.user_spare_id) as user_spare_id, 
            MAX(user_spares.user_service_id) as user_service_id, 
            MAX(user_spares.spare_cost) as spare_cost, 
            MAX(user_spares.service_id) as service_id, 
            MAX(user_spares.service_cost) as service_cost
        ')->join('user_services', 'user_spares.user_service_id', '=', 'user_services.user_service_id')
            ->groupBy('user_spares.spare_id', 'user_services.asset_id');
    
        if (isset($this->request['spare_id']) && !empty($this->request['spare_id'])) {
            $query->where('user_spares.spare_id', $this->request['spare_id']);
        }
    
        if (isset($this->request['spare_type_id']) && !empty($this->request['spare_type_id'])) {
            $query->whereHas('Spare', function ($q) {
                $q->where('spare_type_id', $this->request['spare_type_id']);
            });
        }
    
        if (isset($this->request['asset_id']) && !empty($this->request['asset_id'])) {
            $query->where('user_services.asset_id', $this->request['asset_id']);
        }
    
        if (isset($this->request['asset_type_id']) && !empty($this->request['asset_type_id'])) {
            $query->whereHas('userService.Asset', function ($subQ) {
                $subQ->where('asset_type_id', $this->request['asset_type_id']);
            });
        }
    
        if (
            isset($this->request['from_date']) && !empty($this->request['from_date']) &&
            isset($this->request['to_date']) && !empty($this->request['to_date'])
        ) {
            $fromDate = $this->request['from_date'];
            $toDate = $this->request['to_date'] . ' 23:59:59';
    
            $query->whereBetween('user_services.service_date', [$fromDate, $toDate]);
        }
    
        return $query->get();
    }
}
