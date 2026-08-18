<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectWithdraw extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'project_withdraws';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'project_id',
      'amount',
      'account_bank',
      'account_number',
      'account_name',
      'description',
      'status',
    ];

    public function scopeSuccess($query)
    {
      return $query->where('status', 'accept');
    }

    public function scopePending($query)
    {
      return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
      return $query->where('status', 'failed');
    }

    public function project()
    {
      return $this->belongsTo(Project::class);
    }
}
