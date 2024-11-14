<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTemplateAccessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_template_id',
        'plant_id',
        'area_id',
        'template_zone_id',
        'accessory_type_id',
        'accessory_name',
        'attachment'
    ];

    protected $primaryKey = 'asset_template_accessory_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
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

    public function AccessoryType()
    {
        return $this->belongsTo(AccessoryType::class, 'accessory_type_id');
    }
}
