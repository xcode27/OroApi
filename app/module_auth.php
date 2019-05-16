<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class module_auth extends Model
{
    //
    protected $table = 'module_auths';
    public $timestamps = false;
    protected $guarded = ['updated_at'];

    public function User(){
    	return $this->belongsTo(User::class);
    }

}
