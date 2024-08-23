<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSourceAssetType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_type_id',
        'data_source_id'
    ];

    protected $primaryKey = 'data_source_asset_type_id';

    public function AssetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id', 'asset_type_id');
    }
}
