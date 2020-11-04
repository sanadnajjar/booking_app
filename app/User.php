<?php
/*
|--------------------------------------------------------------------------
| app/User.php *** Copyright netprogs.pl | avaiable only at Udemy.com | further distribution is prohibited  ***
|--------------------------------------------------------------------------
*/

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements \Tymon\JWTAuth\Contracts\JWTSubject
{
    use Notifiable;
    use Booking\Presenters\UserPresenter;

    public static $roles = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'surname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function objects()
    {
        return $this->morphedByMany('App\TouristObject', 'likeable');
    }


    public function articles()
    {
        return $this->morphedByMany('App\Article', 'likeable');
    }


    public function photos()
    {
        return $this->morphMany('App\Photo', 'photoable');
    }


    public function comments()
    {
        return $this->hasMany('App\Comment');
    }


    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }



    public function hasRole(array $roles)
    {

        foreach($roles as $role)
        {

            if(isset(self::$roles[$role]))
            {
                if(self::$roles[$role])  return true;

            }
            else
            {
                self::$roles[$role] = $this->roles()->where('name', $role)->exists();
                if(self::$roles[$role]) return true;
            }

        }


        return false;

    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

