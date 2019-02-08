<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

    protected $fillable = ['type'];

	public function users()
    {
    	return $this->hasMany('App\Users');
    }
}
