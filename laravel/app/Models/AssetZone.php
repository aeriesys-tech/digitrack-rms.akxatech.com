<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetZone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'zone_name'
    ];

    protected $primaryKey = 'asset_zone_id';

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
