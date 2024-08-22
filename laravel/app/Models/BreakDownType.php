<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakDownType extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'break_down_type_code',
        'break_down_type_name'
    ];

    protected $primaryKey = 'break_down_type_id';
}
