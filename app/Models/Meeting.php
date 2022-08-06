<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'start', 'end', 'changed', 'title'];

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'person_meeting');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_meeting');
    }
}
