<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['start', 'end', 'title'];

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'person_meeting');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'employee_meeting');
    }
}
