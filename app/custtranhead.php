<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class custtranhead extends Model
{
    //
     protected $connection = 'mysql2';
     protected $table = 'cust_tran_head';
     public $timestamps = false;
     protected $guarded = ['updated_at'];

}
