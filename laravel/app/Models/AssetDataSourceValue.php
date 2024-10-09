<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDataSourceValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_data_source_id',
        'asset_id',
        'data_source_id',
        'asset_zone_id',
        'data_source_attribute_id',
        'field_value'
    ];

    protected $primaryKey = 'asset_data_source_value_id';

    public function DataSourceAttribute()
    {
        return $this->hasMany(DataSourceAttribute::class, 'data_source_attribute_id', 'data_source_attribute_id');
    }
}
