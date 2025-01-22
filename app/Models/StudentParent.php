<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StudentParent extends Authenticatable
{
    use HasFactory ,HasApiTokens, SoftDeletes, Notifiable;
    protected $fillable = [

        'firsName',
        'lastName',
        'date_of_birth',
        'gender',
        'blood_Type',
        'adress',
        'phone',
        'email',
        'password',
        'last_login',
    ];

    protected $appends =['role'];

    public function getRoleAttribute()
    {
        return 'parent';
    }
    public function students()
    {
        return $this->hasMany(User::class);
    }
    protected $hidden = [
        'created_at',
        'deleted_at',
        'password',
        "email_verified_at",
        'remember_token',
        'last_login'
    ];
}
