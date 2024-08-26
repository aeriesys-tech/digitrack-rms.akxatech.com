<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakDownAttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'break_down_attribute_id',
        'break_down_list_id',
        'field_value'
    ];

    protected $primaryKey = 'break_down_attribute_value_id';

    public function BreakDownAttribute()
    {
        return $this->hasMany(BreakDownAttribute::class, 'break_down_attribute_id', 'break_down_attribute_id');
    }
}
