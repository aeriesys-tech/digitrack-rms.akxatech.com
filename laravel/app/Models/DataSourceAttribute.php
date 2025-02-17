<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSourceAttribute extends Model
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
        'list_parameter_id',
        'field_key'
    ];

    protected $primaryKey = 'data_source_attribute_id';

    public function DataSourceAttributeTypes()
    {
        return $this->hasMany(DataSourceAttributeType::class, 'data_source_attribute_id', 'data_source_attribute_id');
    }

    public function DataSourceAttributeValue()
    {
        return $this->belongsTo(DataSourceAttributeType::class, 'data_source_attribute_id', 'data_source_attribute_id');
    }

    public function DataSourceAttributeValues()
    {
        return $this->hasMany(DataSourceAttributeType::class, 'data_source_attribute_id', 'data_source_attribute_id');
    }

    public function ListParameter()
    {
        return $this->belongsTo(ListParameter::class, 'list_parameter_id');
    }
}
