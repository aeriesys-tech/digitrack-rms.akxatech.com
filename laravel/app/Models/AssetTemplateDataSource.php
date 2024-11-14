<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetTemplateDataSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_source_id',
        'asset_template_id',
        'plant_id',
        'area_id',
        'template_zone_id',
        'data_source_type_id',
        'script'
    ];

    protected $table = 'asset_template_datasources';

    protected $primaryKey = 'asset_template_datasource_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function DataSource()
    {
        return $this->belongsTo(DataSource::class, 'data_source_id');
    }

    public function AssetTemplate()
    {
        return $this->belongsTo(AssetTemplate::class, 'asset_template_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function TemplateZone()
    {
        return $this->belongsTo(TemplateZone::class, 'template_zone_id');
    }

    public function DataSourceType()
    {
        return $this->belongsTo(DataSourceType::class, 'data_source_type_id');
    }

    public function TemplateDataSourceValue()
    {
        return $this->hasMany(TemplateDataSourceValue::class, 'asset_template_datasource_id', 'asset_template_datasource_id');
    }
}
