<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAttribute extends Model
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
    ];

    protected $primaryKey = 'service_attribute_id';

    public function ServiceAttributeTypes()
    {
        return $this->hasMany(ServiceAttributeType::class, 'service_attribute_id', 'service_attribute_id');
    }

    public function ServiceAttributeValue()
    {
        return $this->belongsTo(ServiceAttributeType::class, 'service_attribute_id', 'service_attribute_id');
    }

    public function ServiceAttributeValues()
    {
        return $this->hasMany(ServiceAttributeType::class, 'service_attribute_id', 'service_attribute_id');
    }
}
