<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSourceAttributeType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'data_source_attribute_id',
        'data_source_type_id'
    ];

    protected $primaryKey = 'data_source_attribute_type_id';
    
    public function DataSourceType()
    {
        return $this->belongsTo(DataSourceType::class, 'data_source_type_id', 'data_source_type_id');
    }
}
