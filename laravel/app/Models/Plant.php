<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'plants';
    
    protected $fillable = [
        'plant_code',
        'plant_name',
        'area_id',
        'latitude',
        'longitude',
        'radius'
    ];

    protected $primaryKey = 'plant_id';

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
