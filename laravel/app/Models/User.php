<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_no',
        'role_id',
        'address',
        'avatar',
        'plant_id'
    ];

    protected $primaryKey = 'user_id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function Plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id')->withTrashed();
    }
    
    public function Consent()
    {
        return $this->belongsTo(Consent::class, 'user_id', 'user_id')->where('consent', true);
    }
}
