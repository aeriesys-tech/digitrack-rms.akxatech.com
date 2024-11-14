<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTemplateVariable extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'variable_id',
        'asset_template_id',
        'plant_id',
        'template_zone_id',
        'variable_type_id'
    ];

    protected $primaryKey = 'asset_template_variable_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Variable()
    {
        return $this->belongsTo(Variable::class, 'variable_id');
    }

    public function AssetTemplate()
    {
        return $this->belongsTo(AssetTemplate::class, 'asset_template_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function TemplateZone()
    {
        return $this->belongsTo(TemplateZone::class, 'template_zone_id');
    }

    public function VariableType()
    {
        return $this->belongsTo(VariableType::class, 'variable_type_id');
    }

    public function TemplateVariableValue()
    {
        return $this->hasMany(TemplateVariableValue::class, 'asset_template_variable_id', 'asset_template_variable_id');
    }
}
