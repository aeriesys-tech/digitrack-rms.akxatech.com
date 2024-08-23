<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariableAttributeType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'variable_attribute_id',
        'variable_type_id'
    ];

    protected $primaryKey = 'variable_attribute_type_id';
    
    public function VariableType()
    {
        return $this->belongsTo(VariableType::class, 'variable_type_id', 'variable_type_id');
    }
}