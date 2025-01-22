<?php

namespace App\Models;

use App\Models\ClassType;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassTypeCourse extends Model
{
    use HasFactory, HasApiTokens , SoftDeletes , Notifiable;

    protected $fillable =[
        'coef',
        'course_id',
        'class_type_id',
        'teacher_id',
        'masseH'
    ] ;

    public function classeType(){
        return $this->belongsTo(ClassType::class);
    }

}
