<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadedReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_time',
        'file_name',
        'report_name'
    ];

    protected $table = 'downloaded_reports';
    protected $primaryKey = 'download_report_id';
    
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
