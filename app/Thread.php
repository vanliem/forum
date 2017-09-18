<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ReplyCountScope;

class Thread extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        /*static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });*/
        static::addGlobalScope(new ReplyCountScope);
    }


    public function path($path = null)
    {	
    	if ($path) {
    		return $path;
    	}
    	return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
    	return $this->hasMany(Reply::class)->withCount('favourites');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }
}
