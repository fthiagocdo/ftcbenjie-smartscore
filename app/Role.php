<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Permission;

class Role extends Model
{
    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }

    public function addPermission($permission)
    {
        if(is_string($permission)){
            return $this->permissions()->save(Permission::where('name', '=', $permission)->firstOrFail());
        }else{
            return $this->permissions()->save(Permission::where('name', '=', $permission->name)->firstOrFail());
        }
    }

    public function removePermission($permission)
    {
        if(is_string($permission)){
            return $this->permissions()->detach(Permission::where('name', '=', $permission)->firstOrFail());
        }else{
            return $this->permissions()->detach(Permission::where('name', '=', $permission->name)->firstOrFail());
        }
    }

    public function isRole($role)
    {
        if(is_string($role)){
            return $this->name == $role;
        }else{
            return $this->name == $role->name;
        }
    }

    public function isAdmin()
    {
        return $this->isRole('ADMIN');
    }
}
