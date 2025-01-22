<?php

namespace App\Models;

use App\Models\Classe;
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
    public function classe(){
        return $this ->hasMany(Classe::class);
    }
    public function classeTypeCourse(){
        return $this ->hasMany(ClassTypeCourse::class);
    }
    
}
