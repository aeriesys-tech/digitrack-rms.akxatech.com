<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $primaryKey = 'log_id';
    public $timestamps = true;

    protected $fillable = [
        'log_type', 
        'status', 
        'message',
        'status'
    ];
}
