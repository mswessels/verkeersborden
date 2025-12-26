<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class SocialiteController extends Controller {

	public function facebookLogin()
	{
		return \Socialite::with('facebook')->redirect();
	}

}
