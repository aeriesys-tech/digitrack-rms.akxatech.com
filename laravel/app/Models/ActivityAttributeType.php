<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityAttributeType extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = [
        'activity_attribute_id',
        'reason_id'
    ];

    protected $primaryKey = 'activity_attribute_type_id';

    public function Reason()
    {
        return $this->belongsTo(Reason::class, 'reason_id', 'reason_id');
    }
}
