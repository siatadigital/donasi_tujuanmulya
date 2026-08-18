<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_privileges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'menu_admin_id',
        'can_access',
    ];

    public function menuAdmin() {
        return $this->belongsTo(MenuAdmin::class);
    }
}
