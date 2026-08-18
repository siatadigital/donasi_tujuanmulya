<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Project as Project;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar',
        'cover',
        'password',
        'facebook',
        'instagram_trend',
        'soundcloud_playlist',
        'video_profile',
        'instagram',
        'twitter',
        'soundcloud',
        'youtube',
        'bio',
        'province',
        'city',
        'address',
        'phone',
        'quotes',
        'is_artist',
        'is_superadmin',
        'is_verified',
        'is_internal',
        'fotoktp',
        'birth_date',
        'gender',
        'foto_with_ktp',
        'type_akun',
        'group_privilege_id',
        'provider',
        'provider_id',
        'code_referral',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get user projects
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get user events
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    // get active projects
    public function activeProjects()
    {
        return Project::where('user_id',$this->id)->where('status','active')->get();
    }

    /**
     * Get user socials
     */
    public function socials()
    {
        return $this->hasMany(UserSocial::class);
    }

    public function supporting()
    {
        return $this->hasMany(Supporter::class);
    }

    public function getFacebookAttribute($value)
    {
    	if ($value) {
        	return 'https://www.facebook.com/'. $value;
    	}
    }

    public function getTwitterAttribute($value)
    {
    	if ($value) {
        	return 'https://www.twitter.com/'. $value;
    	}
    }

    public function getInstagramAttribute($value)
    {
    	if ($value) {
        	return 'https://www.instagram.com/'. $value;
    	}
    }

    public function getYoutubeAttribute($value)
    {
    	if ($value) {
        	return 'https://www.youtube.com/'. $value;
    	}
    }

    public function getSoundcloudAttribute($value)
    {
    	if ($value) {
        	return 'https://www.soundcloud.com/'. $value;
    	}
    }

    public function getEvents()
    {
        return $this->hasMany(Event::class);
    }

    public function privileges()
    {
        return $this->hasMany(UserPrivilege::class);
    }

    public function dashboardPrivileges()
    {
        return $this->hasMany(UserDashboardPrivilege::class);
    }
}
