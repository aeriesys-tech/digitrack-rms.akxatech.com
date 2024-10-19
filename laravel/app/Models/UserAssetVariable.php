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
        'asset_zone_id',
        'value'
    ];

    protected $primaryKey = 'user_asset_variable_id';

    public function Variable()
    {
        return $this->belongsTo(Variable::class, 'variable_id');
    }

    public function AssetZone()
    {
        return $this->belongsTo(AssetZone::class, 'asset_zone_id');
    }

    public function UserVariable()
    {
        return $this->belongsTo(UserVariable::class, 'user_variable_id');
    }
}
