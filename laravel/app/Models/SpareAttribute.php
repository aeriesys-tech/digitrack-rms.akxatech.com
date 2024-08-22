<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpareAttribute extends Model
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

    protected $primaryKey = 'spare_attribute_id';

    public function SpareAttributeTypes()
    {
        return $this->hasMany(SpareAttributeType::class, 'spare_attribute_id', 'spare_attribute_id');
    }

    public function SpareAttributeValue()
    {
        return $this->belongsTo(SpareAttributeType::class, 'spare_attribute_id', 'spare_attribute_id');
    }

    public function SpareAttributeValues()
    {
        return $this->hasMany(SpareAttributeType::class, 'spare_attribute_id', 'spare_attribute_id');
    }
}
