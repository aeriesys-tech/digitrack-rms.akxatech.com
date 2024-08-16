<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'user_id',
        'asset_id',
        'reference_no',
        'reference_date',
        'note'
    ];

    protected $primaryKey = 'user_check_id';

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function UserAssetCheck()
    {
        return $this->hasMany(UserAssetCheck::class, 'user_check_id','user_check_id');
    }

    public function UserCheckAttachment()
    {
        return $this->hasMany(UserCheckAttachment::class, 'user_check_id','user_check_id');
    }
}
