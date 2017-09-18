<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ReplyCountScope;

class Thread extends Model
{
    use FavouriteTable;
    
    protected $guarded = [];
    protected $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        /*static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });*/
        static::addGlobalScope(new ReplyCountScope);

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });
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
    	return $this->hasMany(Reply::class);
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
