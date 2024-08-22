<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSourceType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_source_types';

    protected $fillable = [
        'data_source_type_code',
        'data_source_type_name'
    ];

    protected $primaryKey = 'data_source_type_id';
}