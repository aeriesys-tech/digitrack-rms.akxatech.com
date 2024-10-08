<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetServiceValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_service_id',
        'asset_id',
        'service_id',
        // 'asset_zone_id',
        'service_attribute_id',
        'field_value'
    ];

    protected $primaryKey = 'asset_service_value_id';

    public function ServiceAttribute()
    {
        return $this->hasMany(ServiceAttribute::class, 'service_attribute_id', 'service_attribute_id');
    }
}
