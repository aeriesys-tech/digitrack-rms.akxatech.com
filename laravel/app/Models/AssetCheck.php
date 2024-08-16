<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetCheck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'check_id',
        'asset_id',
        'plant_id',
        'lcl',
        'ucl',
        'default_value'
    ];

    protected $primaryKey = 'asset_check_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Check()
    {
        return $this->belongsTo(Check::class, 'check_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
