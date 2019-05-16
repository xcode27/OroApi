<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderProdData extends Model
{
    //
     protected $connection = 'mysql2';
     protected $table = 'order_prod_data';
     public $timestamps = false;
     protected $guarded = ['updated_at'];

     public function products(){

     	return $this->belongsTo('App\Models\Products');
     }

}
