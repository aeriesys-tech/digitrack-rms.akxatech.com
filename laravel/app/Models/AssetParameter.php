<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetParameter extends Model
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

    protected $primaryKey = 'asset_parameter_id';

    public function AssetParameterTypes()
    {
        return $this->hasMany(AssetParameterType::class, 'asset_parameter_id', 'asset_parameter_id');
    }

    public function AssetParameterValue()
    {
        return $this->belongsTo(AssetParameterType::class, 'asset_parameter_id', 'asset_parameter_id');
    }

    public function AssetParameterValues()
    {
        return $this->hasMany(AssetParameterType::class, 'asset_parameter_id', 'asset_parameter_id');
    }
}
