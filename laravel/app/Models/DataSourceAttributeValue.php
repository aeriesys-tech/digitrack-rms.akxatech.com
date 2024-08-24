<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSourceAttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'data_source_attribute_id',
        'data_source_id',
        'field_value'
    ];

    protected $primaryKey = 'data_source_attribute_value_id';

    public function DataSourceAttribute()
    {
        return $this->hasMany(DataSourceAttribute::class, 'data_source_attribute_id', 'data_source_attribute_id');
    }
}
