<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');

Route::get('quiz', 'QuizController@getQuestion');
Route::post('quiz', 'QuizController@getQuestion');
Route::get('quiz/start', 'QuizController@doStart');
Route::get('quiz/resultaat', 'QuizController@getResults');

Route::get('verkeersborden-oefenen', 'QuizController@getStart');
Route::get('links', 'HomeController@links');
Route::get('alle-verkeersborden', 'HomeController@alleBorden');
Route::get('theorie-examen-oefenen', 'HomeController@theorieExamen');

Route::get('gogosupercrawler', 'CbrCrawler@start');
Route::get('start_mailing', 'CbrCrawler@start_mailing');

Route::get('auth/facebook', 'SocialiteController@facebookLogin');
Route::get('auth/twitter', 'SocialiteController@twitterLogin');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'AccountController@postAccount');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
