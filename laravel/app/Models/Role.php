<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'role',
        'description',
    ];

    protected $primaryKey = 'role_id';

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'role_abilities','role_id','ability_id');
    }
}
