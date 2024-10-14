<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'asset_id',
        'location',
        'date',
        'file',
        'torpedo_values'
    ];

    protected $primaryKey = 'campaign_result_id';

    public function Campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'campaign_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'asset_id');
    }
}