<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = ['email'];

    public function meetings(): BelongsToMany
    {
        return $this->belongsToMany(Meeting::class, 'person_meeting');
    }
}
