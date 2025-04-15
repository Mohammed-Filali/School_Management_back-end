<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Deleted User',
            'email' => 'unknown@example.com'
        ]);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'present' => ['color' => 'green', 'text' => 'Present'],
            'absent' => ['color' => 'red', 'text' => 'Absent'],
            'late' => ['color' => 'orange', 'text' => 'Late'],
            'excused' => ['color' => 'blue', 'text' => 'Excused'],
        ];

        $status = $this->status ?? 'absent';
        return [
            'color' => $statuses[$status]['color'] ?? 'gray',
            'text' => $statuses[$status]['text'] ?? 'Unknown'
        ];
    }
}
