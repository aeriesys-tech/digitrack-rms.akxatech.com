<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleAbility extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'ability_id'
    ];

    protected $primaryKey = 'role_ability_id';

    public function Ability()
    {
        return $this->belongsTo(Ability::class,'ability_id');
    }
}
