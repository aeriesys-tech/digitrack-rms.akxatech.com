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
        'cluster_id',
        'latitude',
        'longitude',
        'radius'
    ];

    protected $primaryKey = 'plant_id';

    public function Cluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }
}
