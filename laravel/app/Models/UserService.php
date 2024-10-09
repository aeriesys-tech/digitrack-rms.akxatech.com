<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'user_id',
        'service_no',
        'asset_id',
        'service_date',
        'next_service_date',
        'note',
        'is_latest'
    ];

    protected $primaryKey = 'user_service_id';

    public function UserSpare()
    {
        return $this->hasMany(UserSpare::class, 'user_service_id', 'user_service_id');
    }

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function Service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function AssetZone()
    {
        return $this->belongsTo(AssetZone::class, 'asset_zone_id');
    }
}
