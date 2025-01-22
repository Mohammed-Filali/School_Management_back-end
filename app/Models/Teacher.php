<?php

namespace App\Models;

use App\Models\Course;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use HasFactory,HasApiTokens , SoftDeletes ,Notifiable;
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
        'course_id'
    ];
    protected $appends =['role'];

    public function getRoleAttribute()
    {
        return 'teacher';
    }

    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    protected $hidden = [
        'created_at',
        'deleted_at',
        'password',
        "email_verified_at",
        'remember_token',
    ];



}
