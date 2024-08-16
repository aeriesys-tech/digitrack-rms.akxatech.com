<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetParameterType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_parameter_id',
        'asset_type_id'
    ];

    protected $primaryKey = 'asset_parameter_type_id';
    
    public function AssetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id', 'asset_type_id');
    }
}
