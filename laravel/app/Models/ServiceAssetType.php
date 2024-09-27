<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

// class ServiceAssetType extends Model
// {
//     use HasFactory, SoftDeletes;

//     protected $fillable = [
//         'asset_type_id',
//         'service_id'
//     ];

//     protected $primaryKey = 'service_asset_type_id';

//     public function AssetType()
//     {
//         return $this->belongsTo(AssetType::class, 'asset_type_id', 'asset_type_id');
//     }

//     public function Service()
//     {
//         return $this->belongsTo(Service::class, 'service_id', 'service_id');
//     }
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAssetType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_type_id',
        'service_id'
    ];

    protected $primaryKey = 'service_asset_type_id';

    public function AssetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id', 'asset_type_id');
    }

    public function Service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }
}

