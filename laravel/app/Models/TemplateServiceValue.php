<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateServiceValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_template_service_id',
        'asset_template_id',
        'service_id',
        'template_zone_id',
        'service_attribute_id',
        'field_value'
    ];

    protected $primaryKey = 'template_service_value_id';

    public function ServiceAttribute()
    {
        return $this->hasMany(ServiceAttribute::class, 'service_attribute_id', 'service_attribute_id');
    }
}
