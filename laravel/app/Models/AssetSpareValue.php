<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetSpareValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_spare_id',
        'asset_id',
        // 'asset_zone_id',
        'spare_id',
        'spare_attribute_id',
        'field_value'
    ];

    protected $primaryKey = 'asset_spare_value_id';

    public function SpareAttribute()
    {
        return $this->hasMany(SpareAttribute::class, 'spare_attribute_id', 'spare_attribute_id');
    }
}
