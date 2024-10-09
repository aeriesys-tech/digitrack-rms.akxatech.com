<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetVariable extends Model
{
    use HasFactory;

    protected $fillable = [
        'variable_id',
        'asset_id',
        'plant_id',
        'area_id',
        'asset_zone_id',
        'variable_type_id'
    ];

    protected $primaryKey = 'asset_variable_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Variable()
    {
        return $this->belongsTo(Variable::class, 'variable_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function AssetZone()
    {
        return $this->belongsTo(AssetZone::class, 'asset_zone_id');
    }

    public function VariableType()
    {
        return $this->belongsTo(VariableType::class, 'variable_type_id');
    }

    public function AssetVariableValue()
    {
        return $this->hasMany(AssetVariableValue::class, 'asset_variable_id', 'asset_variable_id');
    }
}
