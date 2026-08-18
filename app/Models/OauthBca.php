<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthBca extends Model
{
    protected $fillable = ['access_token', 'token_type', 'expires_in','expired_at','scope'];
}
