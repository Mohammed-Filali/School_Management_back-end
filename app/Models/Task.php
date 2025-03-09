<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
     'description',
     'status',
     'priority',
     'taskable_id',
      'taskable_type'
    ];

    public function taskable()
{
    return $this->morphTo();
}
}
