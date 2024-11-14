<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_template_id',
        'zone_name',
        'height',
        'diameter'
    ];

    protected $primaryKey = 'template_zone_id';

    public function AssetTemplate()
    {
        return $this->belongsTo(AssetTemplate::class, 'asset_template_id');
    }
}
