<?php

namespace App\Models;

use App\Models\BaseModel;

class Banner extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'link',
      'photo',
      'is_modal_popup',
    ];

}
