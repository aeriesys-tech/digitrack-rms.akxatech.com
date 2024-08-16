<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reason extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reason_code',
        'reason_name'
    ];

    protected $primaryKey = 'reason_id';
}
