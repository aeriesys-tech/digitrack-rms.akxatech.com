<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSpare extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_service_id',
        'spare_id',
        'spare_cost',
        'asset_zone_id',
        'service_id',
        'service_cost'
    ];

    protected $primaryKey = 'user_spare_id';
    
    public function Spare()
    {
        return $this->belongsTo(Spare::class, 'spare_id');
    }

    public function AssetZone()
    {
        return $this->belongsTo(AssetZone::class, 'asset_zone_id');
    }

    public function Service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function userService()
    {
        return $this->hasMany(UserService::class, 'user_service_id', 'user_service_id');
    }
}
