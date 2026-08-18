<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'type', 'value'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'dynamic',
    ];

    /**
     * Override the getCastType to make it dynamically
     *
     * @param  string $key
     * @return string
     */
    protected function getCastType($key)
    {
        if ($key == 'value' && !empty($this->type)) {
            return $this->type;
        }

        return parent::getCastType($key);
    }

}
