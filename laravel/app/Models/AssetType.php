<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asset_type';
    protected $fillable = [
        'asset_type_code',
        'asset_type_name'
    ];

    protected $primaryKey = 'asset_type_id';
}
