<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarUser extends Model
{
    use HasFactory;

    protected $fillable = ['email'];

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'calendar_user_meeting');
    }
}
