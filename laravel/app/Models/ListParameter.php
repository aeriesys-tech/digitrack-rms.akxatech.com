<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListParameter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'list_parameter_name',
        'field_values'
    ];

    protected $primaryKey = 'list_parameter_id';
}
