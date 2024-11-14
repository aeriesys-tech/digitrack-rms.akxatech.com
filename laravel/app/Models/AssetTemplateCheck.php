<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTemplateCheck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id',
        'plant_id',
        'asset_template_id',
        'template_zone_id',
        'check_id',
        'default_value',
        'lcl',
        'ucl'
    ];

    protected $primaryKey = 'asset_template_check_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id')->withTrashed();
    }

    public function Check()
    {
        return $this->belongsTo(Check::class, 'check_id')->withTrashed();
    }

    public function AssetTemplate()
    {
        return $this->belongsTo(AssetTemplate::class, 'asset_template_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id')->withTrashed();
    }

    public function TemplateZone()
    {
        return $this->belongsTo(TemplateZone::class, 'template_zone_id');
    }
}
