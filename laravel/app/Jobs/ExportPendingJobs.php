<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendingJobsExport;
use Illuminate\Support\Facades\Storage;
use App\Models\DownloadedReport;
use Carbon\Carbon;

class ExportPendingJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $userId;

    public function __construct($data, $userId)
    {
        $this->data = $data;
        $this->userId = $userId;
    }

    public function handle()
    {
        $filename = 'pending_' . now()->format('Ymd_His') . '.xlsx';
        
        $directoryPath = public_path('storage/reports');
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $path = $directoryPath . '/' . $filename;
        $excel = Excel::raw(new PendingJobsExport($this->data), \Maatwebsite\Excel\Excel::XLSX);
        file_put_contents($path, $excel);
        
        DownloadedReport::create([
            'user_id' => $this->userId,
            'date_time' => Carbon::now(),
            'file_name' => $filename,
            'report_name' => 'Pending Jobs'
        ]);
    }
}