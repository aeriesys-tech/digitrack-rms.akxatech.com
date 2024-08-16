<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_types';
    
    protected $fillable = [
        'equipment_type_code',
        'equipment_type_name'
    ];

    protected $primaryKey = 'equipment_type_id';
}
