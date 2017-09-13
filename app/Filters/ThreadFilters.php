<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\User;

class ThreadFilters extends Filters
{
    /**
     * @var array
     */
    protected $filters = ['by'];

    /**
     * @param $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

}
