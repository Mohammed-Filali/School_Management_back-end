<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Course extends Model
{
    use HasFactory, HasApiTokens , SoftDeletes , Notifiable;

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

    public function types(){
        return $this ->belongsToMany(ClassType::class);
    }
}
