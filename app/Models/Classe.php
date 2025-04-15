<?php

namespace App\Models;

use App\Models\ClassType;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory, HasApiTokens , SoftDeletes , Notifiable;
    protected $fillable = [
        'name',
        'code',
        'class_type_id',
    ];
    public function classeType(){
        return $this->belongsTo(ClassType::class);
    }
    public function students(){
        return $this->hasMany(User::class);
    }
}
