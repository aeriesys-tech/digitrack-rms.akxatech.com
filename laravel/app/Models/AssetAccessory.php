<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAccessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'plant_id',
        'area_id',
        // 'asset_zone_id',
        'accessory_type_id',
        'accessory_name',
        'attachment'
    ];

    protected $primaryKey = 'asset_accessory_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    // public function AssetZone()
    // {
    //     return $this->belongsTo(AssetZone::class, 'asset_zone_id');
    // }

    public function AccessoryType()
    {
        return $this->belongsTo(AccessoryType::class, 'accessory_type_id');
    }
}
