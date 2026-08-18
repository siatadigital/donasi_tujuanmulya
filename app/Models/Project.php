<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 
        'slug', 
        'summary', 
        'content', 
        'cover', 
        'video', 
        'video_type', 
        'money_target', 
        'money_progress', 
        'category_id', 
        'provinsi_id', 
        'kota_id', 
        'time_end', 
        'time_start', 
        'type_business', 
        'status', 
        'status_progress',
        'is_featured',
        'is_fundraiser',
        'fundraiser_project_id',
        'support_fundraiser',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = array('progress', 'duration', 'funds');


    public function getStatusAttribute($value)
    {
        $attributes = $this->getAttributes();
        $result = '';
        if (isset($attributes['id'])) {
            $project = $this->where('id', $attributes['id'])
                ->whereRaw('money_progress < money_target')
                ->where( 'time_end', '>=' , date('Y-m-d G:i:s'))
                ->first();
            $result = $attributes['status'];

            if (!$project) {
                $result = 'expired';
            }
        }
        
        return $result;
    }

    /**
     * Get user owner
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get supporters
     */
    public function supporters()
    {
        return $this->hasMany(Supporter::class);
    }

    /**
     * Get parent project
     */
    public function parent()
    {
        return $this->belongsTo(Project::class, 'fundraiser_project_id');
    }

    /**
     * Get sub project
     */
    public function children()
    {
        return $this->hasMany(Project::class, 'fundraiser_project_id');
    }

    /**
    * Get updates
    */
    public function updates()
    {
        return $this->hasMany(Update::class);
    }

    /**
     * Get category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get provinsi
     */
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    /**
     * Get kota
     */
    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    /**
     * Get rewards
     */
    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }

    /**
     * Get all comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all withdraws.
     */
    public function withdraws()
    {
        return $this->hasMany(ProjectWithdraw::class);
    }

    public function getProgressAttribute()
    {
        // formula : money_progress / (money_target / 100)
        return round($this->money_progress / ($this->money_target / 100));
    }

    public function getDurationAttribute()
    {
        $time_end = strtotime((string) $this->time_end);
        $created_at = strtotime((string) $this->created_at);
        $datediff = $time_end - $created_at;
        return floor($datediff / (60 * 60 * 24));
    }

    public function getFundsAttribute()
    {
        $totalWithdrawed = $this->withdraws()->where('status', '!=', 'failed')->sum('amount');
        $funds = $this->money_progress - $totalWithdrawed;

        return $funds;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

}
