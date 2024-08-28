<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariableAttribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'field_name',
        'display_name',
        'field_type', 
        'field_values',
        'field_length',
        'is_required',
        'user_id',
        'list_parameter_id'
    ];

    protected $primaryKey = 'variable_attribute_id';

    public function VariableAttributeTypes()
    {
        return $this->hasMany(VariableAttributeType::class, 'variable_attribute_id', 'variable_attribute_id');
    }

    public function VariableAttributeValue()
    {
        return $this->belongsTo(VariableAttributeType::class, 'variable_attribute_id', 'variable_attribute_id');
    }

    public function VariableAttributeValues()
    {
        return $this->hasMany(VariableAttributeType::class, 'variable_attribute_id', 'variable_attribute_id');
    }
}
