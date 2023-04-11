<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModule extends Model
{
    public $timestamps = false;

    public function module(){
        return $this->hasOne(Module::class,'id');
    }

    public function role(){
        return $this->hasOne(Role::class,'id');
    }

}
