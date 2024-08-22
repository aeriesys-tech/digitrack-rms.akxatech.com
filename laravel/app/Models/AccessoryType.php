<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessoryType extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'accessory_type_code',
        'accessory_type_name'
    ];

    protected $primaryKey = 'accessory_type_id';
}