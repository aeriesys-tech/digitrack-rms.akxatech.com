<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetSpare extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id',
        'spare_id',
        'asset_id',
        'plant_id',
        'asset_zone_id',
        'spare_type_id',
        'quantity'
    ];

    protected $primaryKey = 'asset_spare_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Spare()
    {
        return $this->belongsTo(Spare::class, 'spare_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function AssetZone()
    {
        return $this->belongsTo(AssetZone::class, 'asset_zone_id');
    }

    public function SpareType()
    {
        return $this->belongsTo(SpareType::class, 'spare_type_id');
    }
}
