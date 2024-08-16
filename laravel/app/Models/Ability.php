<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $fillable = [
        'ability',
        'description',
        'module_id'
    ];

    protected $primaryKey = 'ability_id';

    public function RoleAbilities()
    {
        return $this->hasMany(RoleAbility::class,'ability_id','ability_id');
    }

    public function Module()
    {
        return $this->belongsTo(Module::class,'module_id');
    }
}
