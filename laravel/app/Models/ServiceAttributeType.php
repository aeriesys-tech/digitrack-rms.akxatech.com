<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAttributeType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_attribute_id',
        'service_type_id'
    ];

    protected $primaryKey = 'service_attribute_type_id';
    
    public function ServiceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id', 'service_type_id');
    }
}
