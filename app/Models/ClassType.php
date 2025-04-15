<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Course;
use App\Models\ClassTypeCourse;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassType extends Model
{
    use HasFactory, HasApiTokens , SoftDeletes , Notifiable ;
    protected $fillable = [
        'id',
        'name',
        'code'
    ];
    public function classe()
    {
        return $this->hasMany(Classe::class);
    }

    // A ClassType has many ClassTypeCourses
    public function classTypeCourses()
    {
        return $this->hasMany(ClassTypeCourse::class);
    }

    // Many-to-many relationship with Course (make sure there's a pivot table like class_type_course or course_class_type)
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
    }

