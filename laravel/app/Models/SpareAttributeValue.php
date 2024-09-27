<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpareAttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'spare_attribute_id',
        'spare_id',
        'field_value'
    ];

    protected $primaryKey = 'spare_attribute_value_id';

    public function SpareAttribute()
    {
        return $this->hasMany(SpareAttribute::class, 'spare_attribute_id', 'spare_attribute_id');
    }

    public function SpareAttributeData()
    {
        return $this->belongsTo(SpareAttribute::class, 'spare_attribute_id', 'spare_attribute_id');
    }

    public function Spare()
    {
        return $this->belongsTo(Spare::class, 'spare_id');
    }
}