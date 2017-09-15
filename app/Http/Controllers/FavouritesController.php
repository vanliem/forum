<?php

namespace App\Http\Controllers;

use App\Favourite;
use Illuminate\Http\Request;
use App\Reply;

class FavouritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favourite();

        return back();
    }
}
