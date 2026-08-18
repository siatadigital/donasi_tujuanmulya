<?php

namespace App\Models;

use App\Models\BaseModel;

class EventUser extends BaseModel
{
    protected $table = 'events_users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id', 'user_id', 'name', 'email', 'phone'];

    /**
     * Get all of the owning commentable models.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get all of the owning commentable models.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
