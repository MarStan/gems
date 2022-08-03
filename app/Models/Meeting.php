<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['start', 'end', 'title'];

    public function calendarUsers()
    {
        return $this->belongsToMany(CalendarUser::class, 'calendar_user_meeting');
    }
}
