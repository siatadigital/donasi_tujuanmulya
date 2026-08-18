<?php

namespace App\Models;

use App\Models\BaseModel;

class PaymentMethodGroup extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_method_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
      'is_active',
    ];


    public function paymentMethodInfak($id){
        return PaymentMethod::where('is_active_infak',1)->where('group_id',$id)->get();
    }

    public function paymentMethodZakat($id){
      return PaymentMethod::where('is_active_zakat',1)->where('group_id',$id)->get();
    }

    public function paymentMethodProject($id){
      return PaymentMethod::where('is_active_campaign',1)->where('group_id',$id)->get();
    }
}
