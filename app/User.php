<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function addRole($role)
    {
        if(is_string($role)){
            return $this->roles()->save(Role::where('name', '=', $role)->firstOrFail());
        }else{
            return $this->roles()->save(Role::where('name', '=', $role->name)->firstOrFail());
        }
    }

    public function removeRole($role)
    {
        if(is_string($role)){
            return $this->roles()->detach(Role::where('name', '=', $role)->firstOrFail());
        }else{
            return $this->roles()->detach(Role::where('name', '=', $role->name)->firstOrFail());
        }
    }

    public function hasRole($role)
    {
        if(is_string($role)){
            return $this->roles->contains('name', $role);
        }else{
            return $role->intersect($this->roles)->count();
        }
    }

    public function hasAdmin()
    {
        return $this->hasRole('ADMIN');
    }
}
