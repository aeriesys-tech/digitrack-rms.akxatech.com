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
        'asset_id',
        'job_no',
        'list_parameter_id',
        'job_date',
        'note'
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

    public function Asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
