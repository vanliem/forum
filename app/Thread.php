<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function path($path = null)
    {	
    	if ($path) {
    		return $path;
    	}
    	
    	return '/threads/' . $this->id;
    }
}
