<?php

namespace App\Models;

use App\Models\Teacher;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory, HasApiTokens, SoftDeletes, Notifiable;

    protected $fillable = [
        'name',
        'desc'
    ];

    public function classtypecourses()
    {
        return $this->hasMany(ClassTypeCourse::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function types()
    {
        return $this->belongsToMany(ClassType::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
    public function teachers(){
        return $this->hasMany(Teacher::class);
    }
}
