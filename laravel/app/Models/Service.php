<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_type_id',
        'service_code',
        'service_name',
        'list_parameter_id'
    ];

    protected $primaryKey = 'service_id';

    public function ServiceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function ServiceAssetTypes()
    {
        return $this->hasMany(ServiceAssetType::class, 'service_id', 'service_id');
    }

    public function ServiceAttributes()
    {
        return $this->hasMany(ServiceAttribute::class, 'service_id', 'service_id');
    }
}
