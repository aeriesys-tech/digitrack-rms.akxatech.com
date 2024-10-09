<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'department_id'
    ];

    protected $primaryKey = 'asset_department_id';

    public function Department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
