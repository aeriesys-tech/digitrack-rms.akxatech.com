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

    public function ServiceAttributeData()
    {
        return $this->belongsTo(ServiceAttribute::class, 'service_attribute_id', 'service_attribute_id');
    }

    public function Service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}


// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

// class ServiceAttributeValue extends Model
// {
//     use HasFactory, SoftDeletes;

//     protected $fillable = [
//         'service_id',
//         'service_attribute_id',
//         'field_value',
//     ];

//     protected $primaryKey = 'service_attribute_value_id'; // Adjust if necessary

//     public function Service()
//     {
//         return $this->belongsTo(Service::class);
//     }

//     public function ServiceAttribute()
//     {
//         return $this->belongsTo(ServiceAttribute::class);
//     }
// }
