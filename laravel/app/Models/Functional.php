<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Functional extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'functional_code',
        'functional_name'
    ];

    protected $primaryKey = 'functional_id';
}