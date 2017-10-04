<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarsController extends Controller
{
    public function store($user)
    {
        request()->validate([
            'avatar' => [
                'required',
                'image'
            ]
        ]);

        auth()->user()->update([
            'avatar_path' => \request()->file('avatar')->store('avatars', 'public')
        ]);

        return back();
    }
}
