<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kota';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['kota_name'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get provinsi
     */
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
}
