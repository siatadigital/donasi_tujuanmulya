<?php

namespace App\Models;

use App\Models\BaseModel;

class SupporterDetail extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supporter_id', 
        'project_id', 
        'name', 
        'item', 
        'price', 
        'quantity', 
    ];

    /**
     * Get support owned
     */
    public function supporter()
    {
        return $this->belongsTo(Supporter::class);
    }

    /**
     * Get project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
