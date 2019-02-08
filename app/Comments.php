<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'comments';

    protected $fillable = ['title', 'description', 'date', 'id_event', 'id_user'];

    public function events()
    {
        return $this->belongsTo('App\Events');
    }

    public function users()
    {
        return $this->belongsTo('App\Users');
    }

}
