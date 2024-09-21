<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_id',
        'asset_id',
        'plant_id',
        'area_id',
        'asset_zone_id',
        'service_type_id'
    ];

    protected $primaryKey = 'asset_service_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Service()
    {
        return $this->belongsTo(Service::class, 'service_id');
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

    public function ServiceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function AssetServiceValue()
    {
        return $this->hasMany(AssetServiceValue::class, 'asset_service_id', 'asset_service_id');
    }
}
