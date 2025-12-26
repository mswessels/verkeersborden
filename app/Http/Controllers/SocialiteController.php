<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SocialiteUser;

class SocialiteController extends Controller {

	public function facebookLogin(SocialiteUser $socialiteUser, Request $request)
	{
		return $socialiteUser->execute($request->has('code'), 'facebook', $this);
	}
	
	public function twitterLogin(SocialiteUser $socialiteUser, Request $request)
	{
		return $socialiteUser->execute($request->has('oauth_token'), 'twitter', $this);
	}
	
	public function userHasLoggedIn($user)
	{
		return redirect('quiz/resultaat');
	}

}