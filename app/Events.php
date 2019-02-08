<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'events';

    protected $fillable = ['title', 'description', 'image', 'lat', 'lon',
		'url', 'id_user', 'id_type'];

    public function users()
    {
        return $this->belongsTo('App\Users');
    }

    public function types()
    {
        return $this->belongsTo('App\Types');
    }

    public function comments()
    {
        return $this->hasMany('App\Comments');
    }

}
