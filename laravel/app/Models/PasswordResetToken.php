<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp', 
        'token', 
    ];

    protected $primaryKey = 'id';
    protected $table = 'password_reset_tokens';
}
