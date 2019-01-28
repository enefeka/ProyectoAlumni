<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacity extends Model
{
    protected $table = 'privacities';

    protected $fillable = ['phone', 'location'];

	public function users()
    {
    	return $this->belongsTo('App\Users');
    }
}
