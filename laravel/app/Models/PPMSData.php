<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PPMSData extends Model
{
    use HasFactory;

    protected $table = 'ppms_datas';
    public $timestamps = true;
    protected $primaryKey = 'ppms_data_id';

    protected $fillable = [
        'insert_date', 
        'heat_no', 
        'grade',
        're_treat',
        'holding_time',
        'processing_time',
        'ladle_number',
        'o2_ppm',
        'oxygen_after_celoxa',
        'heat_size',
        'al2_addition_bar',
        'al2_addition_coll',
        'tapping_temperature',
        'lf_in_sulphur',
        'lf_in_temperature',
        'lime_consumption',
        'tundish_temperature',
        'super_heat_avg',
        'super_heat_max',
        'lifting_temperature',
        'lf_slag_report'
    ];
}
