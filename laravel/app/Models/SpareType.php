<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpareType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'spare_type_code',
        'spare_type_name'
    ];

    protected $primaryKey = 'spare_type_id';
}
