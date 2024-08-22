<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpareAttributeType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'spare_attribute_id',
        'spare_type_id'
    ];

    protected $primaryKey = 'spare_attribute_type_id';
    
    public function SpareType()
    {
        return $this->belongsTo(SpareType::class, 'spare_type_id', 'spare_type_id');
    }
}
