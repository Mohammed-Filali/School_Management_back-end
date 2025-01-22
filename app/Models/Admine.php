<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admine extends Authenticatable
{
    use HasFactory, HasApiTokens , SoftDeletes , Notifiable;
    protected $fillable = [
        'firsName',
        'lastName ',
        'date_of_birth',
        'adress',
        'phone',
        'email',
        'password',
    ];

    protected $appends =['role'];

    public function getRoleAttribute()
    {
        return 'admin';
    }

    protected $hidden = [
        'password',
        "email_verified_at",
        'remember_token',
    ];
}
