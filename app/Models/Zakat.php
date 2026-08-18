<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Zakat extends Model
{
    protected $table = 'zakat';
    /**
     * Fillable attribute.
     *
     * @var array
     */
    protected $fillable = [
      'type',
      'amount',
      'fullname',
      'email',
      'phone',
      'city',
      'is_anonim',
      'notes',
      'status',
      'is_payment_confirmed',
      'payment_confirm_at',
      'snap_token',
      'payment_method',
      'unique_code',
      'va_number',
      'redirect_url',
      'expired_at',
      'code_referral',
      'is_checked',
      'check_note',
      'sent_expired_email',
    ];

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
        $this->attributes['is_payment_confirmed'] = TRUE;
        $this->attributes['payment_confirm_at'] = date('Y-m-d H:i:s');
        $this->attributes['status'] = 'success';
        self::save();
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
        return $query->where(\DB::raw('zakat.status'), 'success');
    }

    public function scopePending($query)
    {
        $now = date('Y-m-d H:i:s');
        return $query->where(\DB::raw('zakat.status'), 'pending');
    }

    public function scopeExpired($query)
    {
        $now = date('Y-m-d H:i:s');
        return $query->where(\DB::raw('zakat.status'), 'expired');
    }

    public function scopeFailed($query)
    {
        return $query->where(\DB::raw('zakat.status'), 'failed');
    }
}