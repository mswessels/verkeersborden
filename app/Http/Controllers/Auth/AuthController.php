<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('getLogout');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function getLogout()
    {
        Auth::logout();

        return redirect('/');
    }
}
