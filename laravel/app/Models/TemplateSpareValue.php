<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TemplateSpareValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_template_id',
        'spare_id',
        'asset_template_spare_id',
        'template_zone_id',
        'spare_attribute_id',
        'field_value'
    ];

    protected $primaryKey = 'template_spare_value_id';

    public function SpareAttribute()
    {
        return $this->hasMany(SpareAttribute::class, 'spare_attribute_id', 'spare_attribute_id');
    }
}
