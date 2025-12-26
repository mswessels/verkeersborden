<?php namespace App;

use Illuminate\Contracts\Auth\Guard as Authenticator;
use Laravel\Socialite\Contracts\Factory as Socialite;
use App\Repositories\UserRepository as UserRepository;

use Input;

class SocialiteUser {
	
	private $users;
	private $socialite; 
	private $auth;
	
	public function __construct(UserRepository $users, Socialite $socialite, Authenticator $auth)
	{		
		$this->users = $users;
		$this->socialite = $socialite;
		$this->auth = $auth;	
	}
	
	public function execute($hasCode, $driver, $listener)
	{	
		if( ! $hasCode ) return $this->getAuthorizationFirst($driver);
		
		$user = $this->users->findByUsernameOrCreate( $this->getUserByDriver( $driver ) ); 
		
		$this->auth->login($user, true);
		
		return $listener->userHasLoggedIn($user);
	}
	
	private function getAuthorizationFirst($driver)
	{
		return $this->socialite->driver($driver)->redirect();
	}
	
	private function getUserByDriver($driver)
	{
		return $this->socialite->driver($driver)->user();
	}
}