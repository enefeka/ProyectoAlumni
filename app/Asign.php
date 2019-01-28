<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asign extends Model
{
    protected $table = 'asigns';

    protected $fillable = ['id_event', 'id_group'];

}
