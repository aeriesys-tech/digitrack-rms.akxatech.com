<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssetCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_check_id',
        'check_id',
        'asset_check_id',
        'field_name',
        'field_type',
        'default_value',
        'is_required',
        'lcl',
        'ucl',
        'field_values',
        'order',
        'value'
    ];

    protected $primaryKey = 'user_asset_check_id';

    public function UserCheck()
    {
        return $this->belongsTo(UserCheck::class, 'user_check_id');
    }

    public function Check()
    {
        return $this->belongsTo(Check::class, 'check_id');
    }
}
