<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plant_id',
        'asset_code',
        'asset_name',
        'asset_type_id',
        'longitude',
        'latitude',
        'department_id',
        'section_id',
        'radius'
    ];

    protected $primaryKey = 'asset_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function AssetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    public function AssetParameters()
    {
        return $this->hasMany(AssetParameter::class, 'asset_type_id', 'asset_type_id');
    }

    public function Department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function Section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
