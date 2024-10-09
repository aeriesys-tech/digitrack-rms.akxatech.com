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

    public function AssetVariable()
    {
        return $this->hasMany(AssetVariable::class, 'variable_id', 'variable_id');
    }

    public function VariableType()
    {
        return $this->belongsTo(VariableType::class, 'variable_type_id');
    }

    public function VariableAssetTypes()
    {
        return $this->hasMany(VariableAssetType::class, 'variable_id', 'variable_id');
    }

    public function VariableAttributes()
    {
        return $this->hasMany(VariableAttribute::class, 'variable_id', 'variable_id');
    }
    
    public function getvariableValues($variable_id)
    {
        $variablevalue = VariableAttributeValue::where('variable_id', $variable_id)->get();
        return $variablevalue;
    }
}