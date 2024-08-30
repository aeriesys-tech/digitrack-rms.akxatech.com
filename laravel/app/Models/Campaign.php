<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'datasource',
        'file'
    ];

    protected $primaryKey = 'campaign_id';

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'asset_id');
    }
}
