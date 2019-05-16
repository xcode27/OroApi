<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderprodhead extends Model
{
    //
     protected $connection = 'mysql2';
     protected $table = 'order_prod_head';
     public $timestamps = false;
     protected $guarded = ['updated_at'];
}
