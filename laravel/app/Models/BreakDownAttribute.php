<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakDownAttribute extends Model
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

    protected $primaryKey = 'break_down_attribute_id';

    public function BreakDownAttributeTypes()
    {
        return $this->hasMany(BreakDownAttributeType::class, 'break_down_attribute_id', 'break_down_attribute_id');
    }

    public function BreakDownAttributeValue()
    {
        return $this->belongsTo(BreakDownAttributeType::class, 'break_down_attribute_id', 'break_down_attribute_id');
    }

    public function BreakDownAttributeValues()
    {
        return $this->hasMany(BreakDownAttributeType::class, 'break_down_attribute_id', 'break_down_attribute_id');
    }
}
