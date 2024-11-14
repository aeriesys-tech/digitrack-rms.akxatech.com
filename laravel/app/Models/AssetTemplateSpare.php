<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTemplateSpare extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id',
        'spare_id',
        'asset_template_id',
        'plant_id',
        'template_zone_id',
        'spare_type_id',
        'quantity'
    ];

    protected $primaryKey = 'asset_template_spare_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Spare()
    {
        return $this->belongsTo(Spare::class, 'spare_id');
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

    public function SpareType()
    {
        return $this->belongsTo(SpareType::class, 'spare_type_id');
    }

    public function TemplateSpareValue()
    {
        return $this->hasMany(TemplateSpareValue::class, 'asset_template_spare_id', 'asset_template_spare_id');
    }
}
