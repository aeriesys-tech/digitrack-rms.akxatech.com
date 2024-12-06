<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;  
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProcessRegistersExport;
use App\Models\UserVariable;
use App\Models\DownloadedReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ExportProcessRegistersJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $request;
    protected $userId;

    public function __construct($request, $userId)
    {
        $this->request = $request;
        $this->userId = $userId;
    }

    public function handle()
    {
        $processes = $this->fetchProcesses();

        $filename = 'process_register_' . now()->format('Ymd_His') . '.xlsx';
        $directoryPath = public_path('storage/reports');

        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $path = $directoryPath . '/' . $filename;
        $excel = Excel::raw(new ProcessRegistersExport($processes), \Maatwebsite\Excel\Excel::XLSX);
        file_put_contents($path, $excel);

        DownloadedReport::create([
            'user_id' => $this->userId,
            'date_time' => Carbon::now(),
            'file_name' => $filename,
            'report_name' => 'Process Register',
        ]);
    }

    private function fetchProcesses()
    {
        $query = UserVariable::query()->with(['UserAssetVariable.AssetZone', 'UserAssetVariable.Variable']);

        if (!empty($this->request['asset_id'])) {
            $query->where('asset_id', $this->request['asset_id']);
        }

        if (!empty($this->request['department_id'])) {
            $query->whereHas('Asset', function ($q) {
                $q->whereHas('AssetDepartment', function ($q2) {
                    $q2->where('department_id', $this->request['department_id']);
                });
            });
        }

        if (!empty($this->request['from_date']) && !empty($this->request['to_date'])) {
            $query->whereBetween('job_date', [
                $this->request['from_date'],
                $this->request['to_date'] . ' 23:59:59',
            ]);
        }

        return $query->get() ?? collect(); 
    }
}
