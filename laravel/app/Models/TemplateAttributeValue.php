<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_attribute_id',
        'asset_template_id',
        'field_value'
    ];

    protected $primaryKey = 'template_attribute_value_id';

    public function AssetAttribute()
    {
        return $this->hasMany(AssetAttribute::class, 'asset_attribute_id', 'asset_attribute_id');
    }
}
