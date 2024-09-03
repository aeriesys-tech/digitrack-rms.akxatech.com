<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'data_source_type_id',
        'data_source_code',
        'data_source_name',
        'list_parameter_id'
    ];

    protected $primaryKey = 'data_source_id';

    public function DataSourceType()
    {
        return $this->belongsTo(DataSourceType::class, 'data_source_type_id');
    }

    public function DataSourceAssetTypes()
    {
        return $this->hasMany(DataSourceAssetType::class, 'data_source_id', 'data_source_id');
    }
}
