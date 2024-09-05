<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetCheck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id',
        'plant_id',
        'asset_id',
        'asset_zone_id',
        'check_id',
        'default_value',
        'lcl',
        'ucl'
    ];

    protected $primaryKey = 'asset_check_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id')->withTrashed();
    }

    public function Check()
    {
        return $this->belongsTo(Check::class, 'check_id')->withTrashed();
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id')->withTrashed();
    }

    public function AssetZone()
    {
        return $this->belongsTo(AssetZone::class, 'asset_zone_id');
    }
}
