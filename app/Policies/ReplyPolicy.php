<?php

namespace App\Policies;

use App\User;
use App\Reply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function view(User $user, Reply $reply)
    {
        //
    }

    /**
     * Determine whether the user can create replies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function delete(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }
}
