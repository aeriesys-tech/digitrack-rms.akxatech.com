<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVariable extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'user_id',
        'asset_id',
        'job_no',
        'job_date',
        'note',
        'asset_zone_id'
    ];

    protected $primaryKey = 'user_variable_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function AssetZone()
    {
        return $this->belongsTo(AssetZone::class, 'asset_zone_id');
    }

    public function UserAssetVariable()
    {
        return $this->hasMany(UserAssetVariable::class, 'user_variable_id','user_variable_id');
    }
}
