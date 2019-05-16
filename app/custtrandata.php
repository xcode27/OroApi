<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class custtrandata extends Model
{
    //
     protected $connection = 'mysql2';
     protected $table = 'cust_tran_data';
     public $timestamps = false;
     protected $guarded = ['updated_at'];
}
