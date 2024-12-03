<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id',
        'plant_id',
        'asset_code',
        'asset_name',
        'no_of_zones',
        'asset_type_id',
        'longitude',
        'latitude',
        'functional_id',
        'section_id',
        'radius',
        'geometry_type',
        'height',
        'diameter',
        'asset_template_id'
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

    public function AssetDepartment()
    {
        return $this->hasMany(AssetDepartment::class, 'asset_id', 'asset_id');
    }

    public function Section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function Functional()
    {
        return $this->belongsTo(Functional::class, 'functional_id');
    }

    public function Zones()
    {
        return $this->hasMany(AssetZone::class, 'asset_id', 'asset_id');
    }

    public function AssetTemplate()
    {
        return $this->belongsTo(AssetTemplate::class, 'asset_template_id');
    }

    public function Department()
    {
        return $this->belongsToMany(Department::class, 'asset_departments', 'asset_id', 'department_id');
    }
}
