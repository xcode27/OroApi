<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreORo extends Model
{
    //
    protected $connection = 'mysql2';
     protected $table = 'mnt_contacts';
     public $timestamps = false;
     protected $guarded = ['updated_at'];
}
