<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetVariableValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_variable_id',
        'asset_id',
        'variable_id',
        'asset_zone_id',
        'variable_attribute_id',
        'field_value'
    ];

    protected $primaryKey = 'asset_variable_value_id';

    public function VariableAttribute()
    {
        return $this->hasMany(VariableAttribute::class, 'variable_attribute_id', 'variable_attribute_id');
    }
}
