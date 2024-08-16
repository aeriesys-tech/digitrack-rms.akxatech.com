<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCheckAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_check_id',
        'attachments'
    ];

    protected $primaryKey = 'user_check_attachment_id';
}
