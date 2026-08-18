<?php

namespace App\Models;

use App\Models\BaseModel;

class Reward extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id', 'title', 'description', 'content', 'cover', 'price', 'max_name_count', 'status'];

    /**
     * Get project owner
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
