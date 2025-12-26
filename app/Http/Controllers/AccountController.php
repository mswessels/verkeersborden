<?php namespace App\Http\Controllers;

use Validator, Auth, Request;

class AccountController extends Controller {

	public function __construct()
	{
		$this->user = Auth::user();
	}
	
	public function postAccount()
	{
		$input = request()->all();
		
		$v = Validator::make($input, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255',
		]);
		
		if ($v->fails())
		{
			return redirect()->back()->withErrors($v->errors());
		}
				
		$user = \App\User::create([
			'name' 	=> $input['name'],
			'email' => $input['email'],
			'ip' 	=> Request::server('REMOTE_ADDR'),
		]);
		
		Auth::loginUsingId($user->id);
		
		if( $user )
			return redirect('/quiz/resultaat');
		
		return redirect()->back()->withErrors();
	}	

}
