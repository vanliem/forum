<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ReplyCountScope;
use App\Notifications\ThreadWasUpdated;

class Thread extends Model
{
    use FavouriteTable, RecordsActivity;
    
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        //static::addGlobalScope(new ReplyCountScope);

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
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
        $reply = $this->replies()->create($reply);

        //cach 1
        event(new ThreadHasNewReply($this, $reply));

        //cach 2
        //$this->notifySubscriber($reply);

        return $reply;
    }

    protected function notifySubscriber($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each->notify($reply);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

}
