<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetSpare extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'spare_id',
        'asset_id',
        'plant_id'
    ];

    protected $primaryKey = 'asset_spare_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Spare()
    {
        return $this->belongsTo(Spare::class, 'spare_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
