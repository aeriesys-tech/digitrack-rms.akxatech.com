<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssetVariable extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_variable_id',
        'variable_id',
        'asset_variable_id',
        'date_time',
        'value'
    ];

    protected $primaryKey = 'user_asset_variable_id';
}
