<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateDataSourceValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_template_datasource_id',
        'asset_template_id',
        'data_source_id',
        'template_zone_id',
        'data_source_attribute_id',
        'field_value'
    ];

    protected $table = 'template_datasource_values';

    protected $primaryKey = 'template_datasource_value_id';

    public function DataSourceAttribute()
    {
        return $this->hasMany(DataSourceAttribute::class, 'data_source_attribute_id', 'data_source_attribute_id');
    }
}
