<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'issued_datetime',
        'expired_datetime',
        'model_id',
        'model_type',
    ];
}
