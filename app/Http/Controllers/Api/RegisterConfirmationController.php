<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try {
            User::where('confirmation_token', request('token'))
                ->firstOrFail()
                ->confirm();
        } catch (\Exception $exception) {
            return redirect('/threads')
                ->with('flash', 'Unknown token.');
        }

        return redirect('/threads')
            ->with('flash', 'Register success!');
    }
}
