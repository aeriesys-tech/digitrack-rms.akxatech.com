<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakDownAttributeType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'break_down_attribute_id',
        'break_down_type_id'
    ];

    protected $primaryKey = 'break_down_attribute_type_id';
    
    public function BreakDownType()
    {
        return $this->belongsTo(BreakDownType::class, 'break_down_type_id', 'break_down_type_id');
    }
}
