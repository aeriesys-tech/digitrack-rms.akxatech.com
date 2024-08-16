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
        'spare_cost'
    ];

    protected $primaryKey = 'user_spare_id';
    
    public function Spare()
    {
        return $this->belongsTo(Spare::class, 'spare_id');
    }
}
