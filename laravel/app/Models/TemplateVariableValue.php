<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateVariableValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_template_variable_id',
        'asset_template_id',
        'variable_id',
        'template_zone_id',
        'variable_attribute_id',
        'field_value'
    ];

    protected $primaryKey = 'template_variable_value_id';

    public function VariableAttribute()
    {
        return $this->hasMany(VariableAttribute::class, 'variable_attribute_id', 'variable_attribute_id');
    }
}
