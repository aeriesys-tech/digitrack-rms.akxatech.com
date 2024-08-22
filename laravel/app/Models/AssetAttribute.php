<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetAttribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'field_name',
        'display_name',
        'field_type', 
        'field_values',
        'field_length',
        'is_required',
        'user_id',
    ];

    protected $primaryKey = 'asset_attribute_id';

    public function AssetAttributeTypes()
    {
        return $this->hasMany(AssetAttributeType::class, 'asset_attribute_id', 'asset_attribute_id');
    }

    public function AssetAttributeValue()
    {
        return $this->belongsTo(AssetAttributeType::class, 'asset_attribute_id', 'asset_attribute_id');
    }

    public function AssetAttributeValues()
    {
        return $this->hasMany(AssetAttributeType::class, 'asset_attribute_id', 'asset_attribute_id');
    }
}
