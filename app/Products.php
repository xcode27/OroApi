<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    //
     protected $connection = 'mysql2';
     protected $table = 'mnt_products';
     public $timestamps = false;
     protected $guarded = ['updated_at'];
}
