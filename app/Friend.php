<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $table = 'friends';

    protected $fillable = ['id_user_receive', 'id_user_send', 'state'];

    protected $primaryKey = ['id_user_receive', 'id_user_send'];
}
