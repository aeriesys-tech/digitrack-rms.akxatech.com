<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppms extends Model
{
    protected $table = 'ispat.VW_LF_PARA'; // Specify the view
    protected $connection = 'oracle'; // Use Oracle connection
    protected $guarded = []; // Allow mass assignment
    public $timestamps = false; // Views usually don’t have timestamps
}
