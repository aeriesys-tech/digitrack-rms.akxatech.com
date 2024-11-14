<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_template_id',
        'department_id'
    ];

    protected $primaryKey = 'template_department_id';

    public function Department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
