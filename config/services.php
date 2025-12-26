<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => 'deverkeersborden.nl',
		'secret' => 'key-e75a7bd57849e1094d7c67301096582a',
	],

	'mandrill' => [
		'secret' => 'K9tHENiNJHorWj3kwd47Bw',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'secret' => '',
	],
	
	'facebook' => [
		'client_id'  => '903671289765795',
		'client_secret'  => '981609bc873cacb891f78457f27c0a3f',
		'redirect' => 'http://deverkeersborden.nl/auth/facebook',
	],
	
	'twitter' => [
		'client_id'  => 'fe0dJWM0VKFgF41CkQLDnnSj9',
		'client_secret'  => 'RhuTUe2u8B9LEx62hy6nJgrwlsezPUtYMNtNOhmenmp2t8Hq1r',
		'redirect' => 'http://deverkeersborden.nl/auth/twitter',
	],

];
