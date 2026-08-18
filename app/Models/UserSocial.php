<?php

namespace App\Models;

use App\Models\BaseModel;

class UserSocial extends BaseModel
{
    protected $table = 'user_socials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'provider_type', 'provider_id', 'access_token'];

    /**
     * Get user owner
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
