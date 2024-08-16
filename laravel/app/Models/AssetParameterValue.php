<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetParameterValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_parameter_id',
        'asset_id',
        'field_value'
    ];

    protected $primaryKey = 'asset_parameter_value_id';

    public function AssetParameter()
    {
        return $this->hasMany(AssetParameter::class, 'asset_parameter_id', 'asset_parameter_id');
    }
}
