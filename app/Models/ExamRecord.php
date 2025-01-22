<?php

namespace App\Models;

use App\Models\Exam;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamRecord extends Model
{
    use HasFactory,HasApiTokens, SoftDeletes, Notifiable;
    protected $fillable=[
        'exam_id',
        'user_id',
        'comment',
        'note'
    ];

    public function exams(){
        return $this->belongsTo(Exam::class);
    }
}
