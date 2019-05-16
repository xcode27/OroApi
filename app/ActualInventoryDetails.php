<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActualInventoryDetails extends Model
{
    //
     protected $table = 'actual_inventory_details';
     protected $connection = 'mysql2';
	 public $timestamps = false;
	 protected $guarded = ['updated_at'];
}
