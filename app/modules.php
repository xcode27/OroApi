<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\module_auth;

class modules extends Model
{
    //
    protected $table = 'modules';
    public $timestamps = false;
    protected $guarded = ['updated_at'];

    public function ListModule(){
    	return $this->hasMany(module_auth::class);
    }
}
