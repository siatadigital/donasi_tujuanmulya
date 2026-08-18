<?php

namespace App\Models;

use App\Models\BaseModel;

class Supporter extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'project_id', 
        'reward_id', 
        'money', 
        'unique_code', 
        'payment_method', 
        'notes', 
        'status',
        'has_confirm_payment',
        'fullname',
        'email',
        'noauth',
        'phone',
        'city',
        'referal',
        'snap_token',
        'va_number',
        'redirect_url',
        'payment_confirm_at',
        'is_anonim',
        'expired_at',
        'code_referral',
        'is_checked',
        'check_note',
        'sent_expired_email',
    ];

    /**
     * Get user owner
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get reward
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    /**
     * Get details
     */
    public function details()
    {
        return $this->hasMany(SupporterDetail::class);
    }

    public function data_payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'code');
    }
 
    /**
     * Set status to Pending
     *
     * @return void
     */
    public function setPending()
    {
        $this->attributes['status'] = 'pending';
        self::save();
    }
 
    /**
     * Set status to Success
     *
     * @return void
     */
    public function setSuccess()
    {
        $project = Project::find($this->attributes['project_id']);

        if ($project) {
            $this->attributes['has_confirm_payment'] = TRUE;
            $this->attributes['payment_confirm_at'] = date('Y-m-d H:i:s');
            $this->attributes['status'] = 'accept';
            self::save();

            $project->money_progress += $this->attributes['money'];
            $project->save();
        }
    }
 
    /**
     * Set status to Failed
     *
     * @return void
     */
    public function setFailed()
    {
        $this->attributes['status'] = 'failed';
        self::save();
    }
 
    /**
     * Set status to Expired
     *
     * @return void
     */
    public function setExpired()
    {
        $this->attributes['status'] = 'expired';
        $this->attributes['sent_expired_email'] = TRUE;
        self::save();
    }

    public function scopeSuccess($query)
    {
        return $query->where(\DB::raw('supporters.status'), 'accept');
    }

    public function scopePending($query)
    {
        $now = date('Y-m-d H:i:s');
        return $query->where(\DB::raw('supporters.status'), 'pending');
    }

    public function scopeExpired($query)
    {
        $now = date('Y-m-d H:i:s');
        return $query->where(\DB::raw('supporters.status'), 'expired');
    }

    public function scopeFailed($query)
    {
        return $query->where(\DB::raw('supporters.status'), 'failed');
    }

    public function someFunction()
    {
        return 'supporter';

    }
}
