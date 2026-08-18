<?php

namespace App\Models;

use App\Models\BaseModel;

class Blog extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'blog_categories_id', 'title', 'slug', 'description', 'content', 'cover', 'status'];

    /**
     * Get all of the owning commentable models.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog_categories()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'blog_categories_id')->withTrashed();
    }
}
