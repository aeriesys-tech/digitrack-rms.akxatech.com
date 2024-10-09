<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityAttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_activity_id',
        'activity_attribute_id',
        'field_value'
    ];

    protected $primaryKey = 'activity_attribute_value_id';

    public function ActivityAttribute()
    {
        return $this->hasMany(ActivityAttribute::class, 'activity_attribute_id', 'activity_attribute_id');
    }
}
