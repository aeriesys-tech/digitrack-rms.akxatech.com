<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_attribute_id',
        'service_id',
        'field_value'
    ];

    protected $primaryKey = 'service_attribute_value_id';

    public function ServiceAttribute()
    {
        return $this->hasMany(ServiceAttribute::class, 'service_attribute_id', 'service_attribute_id');
    }
}
