<?php

namespace App\Models;

use App\Models\Classe;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'password',
    'date_of_birth',
    'gender',
    'blood_Type',
    'student_parent_id',
    'email',
    'password',
    'adress',  // Add this field
    'phone',    // Add this field
    'classe_id'
];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'email_verified_at',
    'created_at',
    'deleted_at'
  ];
  protected $appends = ['role'];

  public function getRoleAttribute()
  {
    return 'student';
  }

  public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'date_of_birth' => 'date:Y-m-d',
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];
}
