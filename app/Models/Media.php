<?php

namespace App\Models;

use App\Models\BaseModel;

class Media extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'slug', 'description', 'filename', 'mime_type'];

    /**
     * Get user owner
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
