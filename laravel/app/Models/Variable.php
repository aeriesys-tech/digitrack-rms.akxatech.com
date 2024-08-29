<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'variable_type_id',
        'variable_code',
        'variable_name',
        'list_parameter_id'
    ];

    protected $primaryKey = 'variable_id';

    public function VariableType()
    {
        return $this->belongsTo(VariableType::class, 'variable_type_id');
    }

    public function VariableAssetTypes()
    {
        return $this->hasMany(VariableAssetType::class, 'variable_id', 'variable_id');
    }

    public function ListParameter()
    {
        return $this->belongsTo(ListParameter::class, 'list_parameter_id');
    }
}
