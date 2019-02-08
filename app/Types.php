<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'types';

    protected $fillable = ['name'];

	public function events()
    {
    	return $this->hasMany('App\Events');
    }
}
