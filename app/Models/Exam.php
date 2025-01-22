<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\ExamRecord;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory, HasApiTokens , SoftDeletes , Notifiable ;
    protected $fillable = [
        'id',
        'name',
        'classe_id',
        'teacher_id',
        'course_id',
        'type'
    ];

    public function classe(){
        return $this->belongsTo(Classe::class);
    }
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function records(){
        return $this->hasMany(ExamRecord::class);
    }

}
