<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_type';

    protected $fillable = [
        'service_type_code',
        'service_type_name'
    ];

    protected $primaryKey = 'service_type_id';
}
