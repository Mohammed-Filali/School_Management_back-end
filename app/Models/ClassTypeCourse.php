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

        protected $fillable = [
            'coef',
            'course_id',
            'class_type_id',
            'teacher_id',
            'masseH',
        ];

        // Option 2: Rename relationship (if you're using classeType consistently)
        public function classType()
        {
            return $this->belongsTo(ClassType::class, 'class_type_id');
        }


        // Optional: if you have Course and Teacher models, define these too:
        public function course()
        {
            return $this->belongsTo(Course::class);
        }

        public function teacher()
        {
            return $this->belongsTo(User::class, 'teacher_id');
        }


}
