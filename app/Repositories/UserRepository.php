<?php namespace App\Repositories;

use App\User;

class UserRepository {
	
	public function findByUsernameOrCreate( $userData )
	{		
		return User::firstOrCreate([
			'email' => empty( $userData->email ) ? $userData->id : $userData->email,
			'name' => $userData->name,
			'avatar' => $userData->avatar,
		]);
	}
	
}