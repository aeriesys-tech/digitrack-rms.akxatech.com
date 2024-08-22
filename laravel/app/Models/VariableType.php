<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariableType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'variable_type_code',
        'variable_type_name'
    ];

    protected $primaryKey = 'variable_type_id';
}
