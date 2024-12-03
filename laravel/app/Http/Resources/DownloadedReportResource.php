<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadedReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'download_report_id' => $this->download_report_id,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->User),
            'date_time' => $this->date_time,
            'report_name' => $this->report_name,
            'file_name' => $this->file_name ? config('app.asset_url').'reports/'.$this->file_name : null,
        ];
    }
}
