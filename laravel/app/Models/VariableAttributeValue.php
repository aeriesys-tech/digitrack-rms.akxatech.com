<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariableAttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'variable_attribute_id',
        'variable_id',
        'field_value'
    ];

    protected $primaryKey = 'variable_attribute_value_id';

    public function VariableAttribute()
    {
        return $this->hasMany(VariableAttribute::class, 'variable_attribute_id', 'variable_attribute_id');
    }
}
