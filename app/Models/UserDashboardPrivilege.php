<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDashboardPrivilege extends Model
{
    /**
     * Fillable attribute.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'dashboard_item_id',
        'can_access',
    ];

    public function dashboardItem() {
        return $this->belongsTo(DashboardItem::class);
    }
}
