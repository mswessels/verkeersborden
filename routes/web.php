<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CbrCrawler;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('quiz', [QuizController::class, 'getQuestion']);
Route::post('quiz', [QuizController::class, 'getQuestion']);
Route::get('quiz/start', [QuizController::class, 'doStart']);
Route::get('quiz/resultaat', [QuizController::class, 'getResults']);

Route::get('verkeersborden-oefenen', [QuizController::class, 'getStart']);
Route::get('links', [HomeController::class, 'links']);
Route::get('alle-verkeersborden', [HomeController::class, 'alleBorden']);
Route::get('theorie-examen-oefenen', [HomeController::class, 'theorieExamen']);

Route::get('gogosupercrawler', [CbrCrawler::class, 'start']);
Route::get('start_mailing', [CbrCrawler::class, 'start_mailing']);

Route::get('auth/facebook', [SocialiteController::class, 'facebookLogin']);
Route::get('auth/twitter', [SocialiteController::class, 'twitterLogin']);
Route::get('auth/register', [AuthController::class, 'getRegister']);
Route::post('auth/register', [AccountController::class, 'postAccount']);
Route::get('auth/logout', [AuthController::class, 'getLogout']);
