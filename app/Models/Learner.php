<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Learner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
    ];

    public function courses()
{
    return $this->belongsToMany(Course::class, 'enrolments')
        ->withPivot('progress');
}

public function getFullNameAttribute()
{
    return "{$this->firstname} {$this->lastname}";
}
}
