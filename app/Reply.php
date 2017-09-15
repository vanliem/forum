<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected $guarded = [];
	
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourite');
    }

    public function favourite($attributes = [])
    {
        $attributes['user_id'] = auth()->id();

        if (! $this->favourites()->where($attributes)->exists()) {
            return $this->favourites()->create($attributes);
        }
    }
}
