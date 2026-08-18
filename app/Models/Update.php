<?php

namespace App\Models;

use App\Models\BaseModel;

class Update extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id','title','description'];

    /**
    * Get project
    */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
