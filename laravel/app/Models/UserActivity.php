<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_no',
        'activity_date',
        'user_id',
        'plant_id',
        'asset_id',
        'status',
        'activity_status',
        'reason_id',
        'cost',
        'note'
    ];

    protected $primaryKey = 'user_activity_id';

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function Reason()
    {
        return $this->belongsTo(Reason::class, 'reason_id');
    }

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }
}
