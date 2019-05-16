<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActualInventory extends Model
{
    //
     protected $table = 'actual_inventory_head';
     protected $connection = 'mysql2';
	 public $timestamps = false;
	 protected $guarded = ['updated_at'];
}
