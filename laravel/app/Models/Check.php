<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Check extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'field_name',
        'field_type',
        'default_value',
        'is_required',
        'ucl',
        'lcl',
        'field_values',
        'order',
        'frequency_id'
    ];

    protected $primaryKey = 'check_id';

    public function CheckAssetTypes()
    {
        return $this->hasMany(CheckAssetType::class, 'check_id', 'check_id');
    }

    public function Frequency()
    {
        return $this->belongsTo(Frequency::class, 'frequency_id');
    }
}
