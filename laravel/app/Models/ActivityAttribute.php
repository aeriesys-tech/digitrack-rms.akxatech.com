<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityAttribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'field_name',
        'display_name',
        'field_type',
        'field_values',
        'field_length',
        'is_required',
        'user_id',
        'list_parameter_id'
    ];

    protected $primaryKey = 'activity_attribute_id';

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ListParameter()
    {
        return $this->belongsTo(ListParameter::class, 'list_parameter_id');
    }

    public function ActivityAttributeTypes()
    {
        return $this->hasMany(ActivityAttributeType::class, 'activity_attribute_id', 'activity_attribute_id');
    }
}
