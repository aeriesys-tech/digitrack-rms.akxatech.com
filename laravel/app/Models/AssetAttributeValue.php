<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetAttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_attribute_id',
        'asset_id',
        'field_value'
    ];

    protected $primaryKey = 'asset_attribute_value_id';

    public function AssetAttribute()
    {
        return $this->hasMany(AssetAttribute::class, 'asset_attribute_id', 'asset_attribute_id');
    }
}
