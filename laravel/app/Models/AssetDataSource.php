<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDataSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_source_id',
        'asset_id',
        'plant_id',
        'area_id',
        'asset_zone_id',
        'data_source_type_id',
        'script'
    ];

    protected $primaryKey = 'asset_data_source_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function DataSource()
    {
        return $this->belongsTo(DataSource::class, 'data_source_id');
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

    public function DataSourceType()
    {
        return $this->belongsTo(DataSourceType::class, 'data_source_type_id');
    }

    public function AssetDataSourceValue()
    {
        return $this->hasMany(AssetDataSourceValue::class, 'asset_data_source_id', 'asset_data_source_id');
    }
}
