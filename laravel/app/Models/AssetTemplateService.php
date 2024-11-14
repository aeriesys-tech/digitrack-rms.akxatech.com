<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTemplateService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id',
        'plant_id',
        'asset_template_id',
        'template_zone_id',
        'service_id',
        'service_type_id'
    ];

    protected $primaryKey = 'asset_template_service_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Service()
    {
        return $this->belongsTo(Service::class, 'service_id');
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

    public function ServiceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function TemplateServiceValue()
    {
        return $this->hasMany(TemplateServiceValue::class, 'asset_template_service_id', 'asset_template_service_id');
    }
}
