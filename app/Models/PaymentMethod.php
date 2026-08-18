<?php

namespace App\Models;

use App\Models\BaseModel;

class PaymentMethod extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_methods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'code',
        'logo',
        'name',
        'account_name',
        'account_number_zakat',
        'account_number_infak',
        'is_active_infak',
        'is_active_zakat',
        'is_active_campaign',
    ];

    public function group()
    {
        return $this->belongsTo(PaymentMethodGroup::class);
    }

}
