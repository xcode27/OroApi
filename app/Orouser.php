<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orouser extends Model
{
    //
    protected $connection = 'mysql2';
     protected $table = 'users';
     public $timestamps = false;
     protected $guarded = ['updated_at'];
}
