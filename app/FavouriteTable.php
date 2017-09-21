<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

trait FavouriteTable
{
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

    public function isFavourited($attributes = [])
    {
        return !! $this->favourites
            ->where('user_id', auth()->id())
            ->count();
    }

    public function getFavouritesCountAttribute()
    {
        return $this->favourites->count();
    }

    public function unfavourite()
    {
        $attributes['user_id'] = auth()->id();

        $this->favourites()->where($attributes)->delete();
    }

    public function getIsFavouritedAttribute()
    {
        return $this->isFavourited();
    }
}
