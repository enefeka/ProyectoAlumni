<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Belong extends Model
{
    protected $table = 'belongs';

    protected $fillable = ['id_user', 'id_group'];

}
