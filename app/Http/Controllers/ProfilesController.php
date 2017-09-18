<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
    	return view('profiles.show', [
    		'user' => $user,
    		'threads' => $user->threads()->paginate(10)
    	]);
    }
}
