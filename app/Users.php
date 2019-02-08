<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';

    protected $fillable = ['email', 'password', 'phone', 'username', 'birthday', 
        'is_registered', 'id_rol', 'id_privacity', 'description', 'photo', 'name', 'lon', 'lat'];

    public function roles()
    {
        return $this->belongsTo('App\Roles');
    }

    public function privacities()
    {
        return $this->belongsTo('App\Privacity');
    }

    public function events()
    {
    	return $this->hasMany('App\Events');
    }
}
