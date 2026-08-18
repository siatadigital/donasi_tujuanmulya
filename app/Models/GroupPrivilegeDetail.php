<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPrivilegeDetail extends Model
{
    
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'group_privilege_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_privilege_id',
        'menu_admin_id',
    ];

    public function menuAdmin() {
        return $this->belongsTo(MenuAdmin::class);
    }

    public function groupPrivilege() {
        return $this->belongsTo('App\GroupPrivilege');
    }
}
