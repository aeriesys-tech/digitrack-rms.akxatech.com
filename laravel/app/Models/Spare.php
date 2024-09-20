<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spare extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'spare_type_id',
        'spare_code',
        'spare_name',
        'list_parameter_id'
    ];

    protected $primaryKey = 'spare_id';

    public function SpareType()
    {
        return $this->belongsTo(SpareType::class, 'spare_type_id');
    }

    public function SpareAssetTypes()
    {
        return $this->hasMany(SpareAssetType::class, 'spare_id', 'spare_id');
    }

    public function AssetSpare()
    {
        return $this->hasMany(AssetSpare::class, 'spare_id', 'spare_id');
    }
}
