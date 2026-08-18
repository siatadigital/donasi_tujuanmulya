<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provinsi';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provinsi_name'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get provinsi
     */
    public function kota()
    {
        return $this->hasMany(Kota::class);
    }
}
