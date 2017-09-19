<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use FavouriteTable, RecordsActivity;
    
	protected $guarded = [];
	protected $with = ['owner', 'favourites'];

    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
