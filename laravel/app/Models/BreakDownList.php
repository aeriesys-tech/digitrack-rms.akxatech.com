<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakDownList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'break_down_type_id',
        'break_down_list_code',
        'break_down_list_name',
        'list_parameter_id'
    ];

    protected $primaryKey = 'break_down_list_id';

    public function BreakDownType()
    {
        return $this->belongsTo(BreakDownType::class, 'break_down_type_id');
    }

    public function BreakDownListAssetTypes()
    {
        return $this->hasMany(BreakDownListAssetType::class, 'break_down_list_id', 'break_down_list_id');
    }
}
