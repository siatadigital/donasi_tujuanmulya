<?php

namespace App\Models;

use App\Models\BaseModel;

class Event extends BaseModel
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['user_id', 'title', 'slug', 'cover', 'schedule', 'location','city', 'htm', 'description','city', 'lng', 'lat', 'status'];

    protected $appends = ['count_attendance'];

    public function getCountAttendanceAttribute() {
        return $this->hasMany(EventUser::class, 'event_id')->count();
    }

    /**
    * Get all of the owning commentable models.
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(EventUser::class, 'event_id');
    }
}
