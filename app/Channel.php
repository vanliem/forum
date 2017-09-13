<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Channel
 * @package App
 */
class Channel extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
    	return $this->hasMany(Thread::class);
    }


    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        parent::getRouteKeyName();

        return 'slug';
    }


}
